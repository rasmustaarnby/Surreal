<?php

namespace Laragear\Surreal\Query;

use Carbon\CarbonInterval;
use DateInterval;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\JoinClause;
use RuntimeException;
use function array_filter;
use function array_keys;
use function array_map;
use function collect;
use function end;
use function explode;
use function implode;
use function is_array;
use function is_null;
use function is_numeric;
use function is_string;
use function json_encode;
use function preg_replace;
use function preg_split;
use function reset;
use function str_contains;
use function str_replace;
use function stripos;
use function substr;
use function trim;

class SurrealGrammar extends Grammar
{
    /**
     * The string that acts as a placeholder for bindings.
     *
     * @var string
     */
    public const BINDING_STRING = '$?';

    /**
     * The character that signals object/array traverse.
     *
     * @var string
     */
    public const JSON_SEPARATOR = '.';

    /**
     * List of all accepted SurrealDB statements.
     *
     * @var string[]
     */
    public const STATEMENTS = [
        'USE',
        'LET',
        'BEGIN',
        'CANCEL',
        'COMMIT',
        'IF',
        'ELSE',
        'SELECT',
        'INSERT',
        'CREATE',
        'UPDATE',
        'RELATE',
        'DELETE',
        'DEFINE',
        'REMOVE',
        'INFO',
    ];

    /**
     * Write statements to identify queries.
     *
     * @var string[]
     */
    public const WRITE_STATEMENTS = [
        self::STATEMENTS[8],
        self::STATEMENTS[9],
        self::STATEMENTS[10],
        self::STATEMENTS[11],
        self::STATEMENTS[12],
        self::STATEMENTS[13],
        self::STATEMENTS[14],
    ];

    /**
     * List of all accepted SurrealDB statements.
     *
     * @var string[]
     */
    public const OPERATORS = [
        '=',
        '!=',
        '==',
        '?=',
        '*=',
        '~',
        '!~',
        '?~',
        '*~',
        '<',
        '<=',
        '>',
        '>=',
        '+',
        '-',
        '/',
        '&&',
        '||',
        '∋',
        '∌',
        '⊇',
        '⊃',
        '⊅',
        '∈',
        '∉',
        '⊆',
        '⊂',
        '⊄',
        'OUTSIDE',
        'INTERSECTS',

        // These are aliases for other operators
        'IS',
        'IS NOT',
        'AND',
        'OR',
        'CONTAINS',
        'CONTAINSNOT',
        'CONTAINSALL',
        'CONTAINSANY',
        'CONTAINSNONE',
        'INSIDE',
        'NOTINSIDE',
        'ALLINSIDE',
        'ANYINSIDE',
        'NONEINSIDE',
    ];

    /**
     * Map of operator aliases to their respective value.
     *
     * @var array{string:string}
     */
    public const OPERATOR_ALIASES = [
        self::OPERATORS[30] => self::OPERATORS[0],
        self::OPERATORS[31] => self::OPERATORS[1],
        self::OPERATORS[32] => self::OPERATORS[15],
        self::OPERATORS[33] => self::OPERATORS[16],
        self::OPERATORS[34] => self::OPERATORS[19],
        self::OPERATORS[35] => self::OPERATORS[20],
        self::OPERATORS[36] => self::OPERATORS[21],
        self::OPERATORS[37] => self::OPERATORS[22],
        self::OPERATORS[38] => self::OPERATORS[23],
        self::OPERATORS[39] => self::OPERATORS[24],
        self::OPERATORS[40] => self::OPERATORS[25],
        self::OPERATORS[41] => self::OPERATORS[26],
        self::OPERATORS[42] => self::OPERATORS[27],
        self::OPERATORS[43] => self::OPERATORS[28],
    ];

    /**
     * The components that make up a select clause.
     *
     * @var string[]
     */
    protected $selectComponents = [
        'aggregate',
        'columns',
        'from',
        'wheres',
        'groups',
        'orders',
        'limit',
        'offset',

        // 'parallel',
        // 'split',
        // 'fetch',
        // 'timeout',
        // 'return',
        // 'merge',
        // 'patch',
    ];

    /**
     * The grammar specific operators.
     *
     * @var array
     */
    protected $operators = self::OPERATORS;

    /**
     * Formats the Date Interval into something SurrealDB understands.
     *
     * @param  \DateInterval|string  $interval
     * @return string
     */
    public function getFormattedInterval(DateInterval|string $interval): string
    {
        $interval = is_string($interval)
            ? CarbonInterval::fromString($interval)
            : CarbonInterval::instance($interval);

        // SurrealDB (currently) does not support ISO 8601 intervals. We are forced here to
        // take the values from the duration to whatever SurrealDB seems to understand.
        // There may be some data that will be lost, but it's not on me to restore.
        return implode('', array_filter([
            $interval->y ? $interval->y . 'y' : null,
            $interval->m ? $interval->weeks . 'w' : null,
            $interval->dayzExcludeWeeks ? $interval->dayzExcludeWeeks . 'd' : null,
            $interval->h ? $interval->h . 'h' : null,
            $interval->m ? $interval->m . 'm' : null,
            $interval->s ? $interval->s . 's' : null,
            $interval->milliseconds ? $interval->milliseconds . 'ms' : null,
            $interval->microseconds ? $interval->microseconds . 'µs' : null,
        ]));
    }

    /**
     * ------------------------------------------------------------------------
     * Base Grammar
     * ------------------------------------------------------------------------
     */

    /**
     * Wrap a table in keyword identifiers.
     *
     * @param  \Illuminate\Database\Query\Expression|string  $table
     * @return string
     */
    public function wrapTable($table)
    {
        if (!$this->isExpression($table)) {
            return $this->wrap($this->tablePrefix.$table, true);
        }

        return $this->getValue($table);
    }

    /**
     * Wrap a value in keyword identifiers.
     *
     * @param  \Illuminate\Database\Query\Expression|string  $value
     * @param  bool  $prefixAlias
     * @return string
     */
    public function wrap($value, $prefixAlias = false)
    {
        if ($this->isExpression($value)) {
            return $this->getValue($value);
        }

        // If the value being wrapped has a column alias we will need to separate out
        // the pieces so we can wrap each of the segments of the expression on its
        // own, and then join these both back together using the "as" connector.
        if (stripos($value, ' AS ') !== false) {
            return $this->wrapAliasedValue($value, $prefixAlias);
        }

        // If the given value is a JSON selector we will wrap it differently than a
        // traditional value. We will need to split this path and wrap each part
        // wrapped, etc. Otherwise, we will simply wrap the value as a string.
        if ($this->isJsonSelector($value)) {
            return $this->wrapJsonSelector($value);
        }

        return $this->wrapSegments(explode('.', $value));
    }

    /**
     * Wrap a value that has an alias.
     *
     * @param  string  $value
     * @param  bool  $prefixAlias
     * @return string
     */
    protected function wrapAliasedValue($value, $prefixAlias = false)
    {
        $segments = preg_split('/\s+AS\s+/i', $value);

        // If we are wrapping a table we need to prefix the alias with the table prefix
        // as well in order to generate proper syntax. If this is a column of course
        // no prefix is necessary. The condition will be true when from wrapTable.
        if ($prefixAlias) {
            $segments[1] = $this->tablePrefix.$segments[1];
        }

        return $this->wrap($segments[0]).' AS '.$this->wrapValue($segments[1]);
    }

    /**
     * Wrap a single string in keyword identifiers.
     *
     * @param  string  $value
     * @return string
     */
    protected function wrapValue($value)
    {
        return $value === '*' ? $value : '`'.str_replace('`', '``', $value).'`';
    }

    /**
     * Wrap the given JSON selector.
     *
     * @param  string  $value
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function wrapJsonSelector($value)
    {
        return $value;
    }

    /**
     * Determine if the given string is a JSON selector.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isJsonSelector($value)
    {
        return str_contains($value, static::JSON_SEPARATOR);
    }

    /**
     * Get the appropriate query parameter place-holder for a value.
     *
     * @param  mixed  $value
     * @return string
     */
    public function parameter($value): string
    {
        return $this->isExpression($value) ? $this->getValue($value) : static::BINDING_STRING;
    }

    /**
     * ------------------------------------------------------------------------
     * Database Grammar
     * ------------------------------------------------------------------------
     */

    /**
     * Compile a select query into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileSelect(Builder $query): string
    {
        if ($query->aggregate) {
            return $this->compileUnionAggregate($query);
        }

        // If the query does not have any columns set, we'll set the columns to the
        // * character to just get all of the columns from the database. Then we
        // can build the query and concatenate all the pieces together as one.
        $original = $query->columns;

        if (is_null($query->columns)) {
            $query->columns = ['*'];
        }

        // To compile the query, we'll spin through each component of the query and
        // see if that component exists. If it does we'll just call the compiler
        // function for the component which is responsible for making the SQL.
        $sql = trim($this->concatenate(
            $this->compileComponents($query))
        );

        $query->columns = $original;

        return $sql;
    }

    /**
     * Compile an aggregated select clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $aggregate
     * @return string
     */
    protected function compileAggregate(Builder $query, $aggregate)
    {
        $column = $this->columnize($aggregate['columns']);

        // SurrealDB doesn't support DISTINCT, so warn and offer an alternative.
        if (is_array($query->distinct) || ($query->distinct && $column !== '*')) {
            throw new RuntimeException('SurrealDB does not support DISTINCT operations. Use GROUP BY instead.');
        }

        return 'SELECT '.$aggregate['function'].'('.$column.') AS `aggregate`';
    }

    /**
     * Compile the "select *" portion of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $columns
     * @return string|null
     */
    protected function compileColumns(Builder $query, $columns): ?string
    {
        if (!is_null($query->aggregate)) {
            return null;
        }

        if ($query->distinct) {
            throw new RuntimeException('SurrealDB does not support DISTINCT operations. Use GROUP BY instead.');
        }

        return 'SELECT '.$this->columnize($columns);
    }

    /**
     * Compile the "from" portion of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $table
     * @return string
     */
    protected function compileFrom(Builder $query, $table)
    {
        // With a simple JSON encoding trick we can wrap the ID into double quotes.
        return 'FROM '. (str_contains($table, ':') ? json_encode($table) : $this->wrapTable($table));
    }

    /**
     * Compile the "join" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $joins
     * @return string
     */
    protected function compileJoins(Builder $query, $joins): never
    {
        throw new RuntimeException('SurrealDB does not support JOIN operations. Use FETCH or <-/-> instead.');
    }

    /**
     * Format the where clause statements into one string.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $sql
     * @return string
     */
    protected function concatenateWhereClauses($query, $sql)
    {
        if ($query instanceof JoinClause) {
            throw new RuntimeException('SurrealDB does not support JOIN operations. Use FETCH or <-/-> instead.');
        }

        return 'WHERE '.$this->removeLeadingBoolean(implode(' ', $sql));
    }

    /**
     * Compile a bitwise operator where clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return never
     */
    protected function whereBitwise(Builder $query, $where): never
    {
        throw new RuntimeException('SurrealDB does not support bitwise operators. Yell a cloud.');
    }

    /**
     * Compile a "where in" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereIn(Builder $query, $where)
    {
        if (!empty($where['values'])) {
            return $this->wrap($where['column']).' CONTAINS ['.$this->parameterize($where['values']).']';
        }

        return '0 = 1';
    }

    /**
     * Compile a "where not in" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNotIn(Builder $query, $where)
    {
        if (!empty($where['values'])) {
            return $this->wrap($where['column']).' CONTAINSNONE ['.$this->parameterize($where['values']).']';
        }

        return '1 = 1';
    }

    /**
     * Compile a "where not in raw" clause.
     *
     * For safety, whereIntegerInRaw ensures this method is only used with integer values.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNotInRaw(Builder $query, $where)
    {
        if (!empty($where['values'])) {
            return $this->wrap($where['column']).' CONTAINSNONE ['.implode(', ', $where['values']).']';
        }

        return '1 = 1';
    }

    /**
     * Compile a "where in raw" clause.
     *
     * For safety, whereIntegerInRaw ensures this method is only used with integer values.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereInRaw(Builder $query, $where)
    {
        if (!empty($where['values'])) {
            return $this->wrap($where['column']).' CONTAINS ['.implode(', ', $where['values']).']';
        }

        return '0 = 1';
    }

    /**
     * Compile a "where null" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNull(Builder $query, $where)
    {
        return $this->wrap($where['column']).' IS null';
    }

    /**
     * Compile a "where not null" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNotNull(Builder $query, $where)
    {
        return $this->wrap($where['column']).' IS NOT null';
    }

    /**
     * Compile a "between" where clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereBetween(Builder $query, $where)
    {
        $between = $where['not'] ? 'NOTINSIDE' : 'INSIDE';

        $min = $this->parameter(is_array($where['values']) ? reset($where['values']) : $where['values'][0]);

        $max = $this->parameter(is_array($where['values']) ? end($where['values']) : $where['values'][1]);

        return $this->wrap($where['column']).' '.$between.' '.$min.' AND '.$max;
    }

    /**
     * Compile a "between" where clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereBetweenColumns(Builder $query, $where)
    {
        $between = $where['not'] ? 'NOTINSIDE' : 'INSIDE';

        $min = $this->wrap(is_array($where['values']) ? reset($where['values']) : $where['values'][0]);

        $max = $this->wrap(is_array($where['values']) ? end($where['values']) : $where['values'][1]);

        return $this->wrap($where['column']).' '.$between.' '.$min.' AND '.$max;
    }

    /**
     * Compile a "where date" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereDate(Builder $query, $where)
    {
        return $this->dateBasedWhere('day', $query, $where);
    }

    /**
     * Compile a where clause comparing two columns.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereColumn(Builder $query, $where)
    {
        return $this->wrap($where['first']).' '.$where['operator'].' '.$this->wrap($where['second']);
    }

    /**
     * Compile a nested where clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNested(Builder $query, $where)
    {
        if ($query instanceof JoinClause) {
            throw new RuntimeException('SurrealDB does not support old JOIN. Use FETCH or <-/-> instead.');
        }

        return '('.substr($this->compileWheres($where['query']), 6).')';
    }

    /**
     * Compile a where exists clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereExists(Builder $query, $where)
    {
        return 'true = <bool> count(('.$this->compileSelect($where['query']).'))';
    }

    /**
     * Compile a where exists clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNotExists(Builder $query, $where)
    {
        return 'false = <bool> count(('.$this->compileSelect($where['query']).'))';
    }

    /**
     * Compile the "group by" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $groups
     * @return string
     */
    protected function compileGroups(Builder $query, $groups)
    {
        return 'GROUP BY '.$this->columnize($groups);
    }

    /**
     * Compile a single having clause.
     *
     * @param  array  $having
     * @return string
     */
    protected function compileHaving(array $having)
    {
        throw new RuntimeException('SurrealDB does not support HAVING operations.');
    }

    /**
     * Compile the "order by" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $orders
     * @return string
     */
    protected function compileOrders(Builder $query, $orders)
    {
        if (!empty($orders)) {
            return 'ORDER BY '.implode(', ', $this->compileOrdersToArray($query, $orders));
        }

        return '';
    }

    /**
     * Compile the query orders to an array.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $orders
     * @return array
     */
    protected function compileOrdersToArray(Builder $query, $orders)
    {
        return array_map(function ($order) {
            // Since the direction may be "COLLATE ASC" or "NUMERIC DESC", separate them
            if (str_contains(' ', $order['direction'])) {
                [$order['direction'], $order['mode']] = explode(' ', $order['direction']);
            }

            if ($order['sql']) {
                return $order['sql'];
            }

            return $this->wrap($order['column']).' '.($order['mode'] ? $order['mode'].' ' : '').$order['direction'];
        }, $orders);
    }

    /**
     * Compile the random statement into SQL.
     *
     * @param  string  $seed
     * @return string
     */
    public function compileRandom($seed)
    {
        return 'RAND()';
    }

    /**
     * Compile the "limit" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $limit
     * @return string
     */
    protected function compileLimit(Builder $query, $limit)
    {
        return 'LIMIT '.(int) $limit;
    }

    /**
     * Compile a single union statement.
     *
     * @param  array  $union
     * @return string
     */
    protected function compileUnion(array $union)
    {
        throw new RuntimeException('SurrealDB does not support UNION operations. Use FETCH or <-/-> instead.');
    }

    /**
     * Compile an exists statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileExists(Builder $query)
    {
        $select = $this->compileSelect($query->limit(1));

        return "SELECT {$this->wrap('exists')} FROM {exists: <bool> count(($select))}";
    }

    /**
     * Compile an insert statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $values
     * @return string
     */
    public function compileInsert(Builder $query, array $values)
    {
        // Essentially we will force every insert to be treated as a batch insert which
        // simply makes creating the SQL easier for us since we can utilize the same
        // basic routine regardless of an amount of records given to us to insert.
        $table = $this->wrapTable($query->from);

        if (empty($values)) {
            return "CREATE $table";
        }

        if (! is_array(reset($values))) {
            $values = [$values];
        }

        $columns = $this->columnize(array_keys(reset($values)));

        // We need to build a list of parameter place-holders of values that are bound
        // to the query. Each insert should have the exact same number of parameter
        // bindings so we will loop through the record and parameterize them all.
        $parameters = collect($values)->map(function ($record) {
            return '('.$this->parameterize($record).')';
        })->implode(', ');

        return "INSERT INTO $table ($columns) VALUES $parameters";
    }

    /**
     * Compile an insert ignore statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $values
     * @return string
     *
     * @throws \RuntimeException
     */
    public function compileInsertOrIgnore(Builder $query, array $values)
    {
        throw new RuntimeException('SurrealDB does not support inserting while ignoring errors.');
    }

    /**
     * Compile an insert statement using a subquery into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $columns
     * @param  string  $sql
     * @return string
     */
    public function compileInsertUsing(Builder $query, array $columns, string $sql)
    {
        return "INSERTO INTO {$this->wrapTable($query->from)} ({$this->columnize($columns)}) $sql";
    }

    /**
     * Compile an update statement without joins into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $table
     * @param  string  $columns
     * @param  string  $where
     * @return string
     */
    protected function compileUpdateWithoutJoins(Builder $query, $table, $columns, $where)
    {
        return "UPDATE {$table} SET {$columns} {$where}";
    }

    /**
     * Compile an "upsert" statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $values
     * @param  array  $uniqueBy
     * @param  array  $update
     * @return string
     *
     * @throws \RuntimeException
     */
    public function compileUpsert(Builder $query, array $values, array $uniqueBy, array $update)
    {
        $sql = $this->compileInsert($query, $values);

        $sql .= ' ON DUPLICATE KEY UPDATE ('.$this->columnize($uniqueBy).') ';

        $columns = collect($update)->map(function ($value, $key) {
            return is_numeric($key)
                ? $this->wrap($value).' = '.$this->wrapValue('excluded').'.'.$this->wrap($value)
                : $this->wrap($key).' = '.$this->parameter($value);
        })->implode(', ');

        return $sql.$columns;
    }
    /**
     * Compile a delete statement without joins into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $table
     * @param  string  $where
     * @return string
     */
    protected function compileDeleteWithoutJoins(Builder $query, $table, $where)
    {
        return "DELETE FROM {$table} {$where}";
    }

    /**
     * Compile a truncate table statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return array
     */
    public function compileTruncate(Builder $query)
    {
        throw new RuntimeException('SurrealDB does not support TRUNCATE operations.');
    }

    /**
     * Compile the lock into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  bool|string  $value
     * @return string
     */
    protected function compileLock(Builder $query, $value)
    {
        throw new RuntimeException('SurrealDB already uses pessimistic locking by design.');
    }

    /**
     * Determine if the grammar supports savepoints.
     *
     * @return bool
     */
    public function supportsSavepoints()
    {
        return false;
    }

    /**
     * Remove the leading boolean from a statement.
     *
     * @param  string  $value
     * @return string
     */
    protected function removeLeadingBoolean($value)
    {
        return preg_replace('/AND |OR /i', '', $value, 1);
    }

    /**
     * Compile a date based where clause.
     *
     * @param  string  $type
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function dateBasedWhere($type, Builder $query, $where)
    {
        $value = $this->parameter($where['value']);

        return 'time::group('.$this->wrap($where['column']).', '.$type.') '.$where['operator'].' '.$value;
    }
}
