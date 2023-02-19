# Surreal

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laragear/surreal.svg)](https://packagist.org/packages/laragear/surreal)
[![Latest stable test run](https://github.com/Laragear/WebAuthn/workflows/Tests/badge.svg)](https://github.com/Laragear/Surreal/actions)
[![Codecov coverage](https://codecov.io/gh/Laragear/Surreal/branch/master/graph/badge.svg?token=jAnyxbeNPX)](https://codecov.io/gh/Laragear/Surreal)
[![CodeClimate Maintainability](https://api.codeclimate.com/v1/badges/d7dbd6836bcf5b4b90c6/maintainability)](https://codeclimate.com/github/Laragear/Surreal/maintainability)
[![Sonarcloud Status](https://sonarcloud.io/api/project_badges/measure?project=Laragear_Surreal&metric=alert_status)](https://sonarcloud.io/dashboard?id=Laragear_Surreal)
[![Laravel Octane Compatibility](https://img.shields.io/badge/Laravel%20Octane-Compatible-success?style=flat&logo=laravel)](https://laravel.com/docs/9.x/octane#introduction)

Use a [SurrealDB](https://surrealdb.com/) database in your Laravel application.

```php
use Illuminate\Support\Facades\DB;

$user = DB::connection('surreal')->find('article:1', ['title']);

// [    
//    'title' => 'Let me tell you why SurrealDB is awesome',
// ]
```

## Become a sponsor

[![](.github/assets/support.png)](https://github.com/sponsors/DarkGhostHunter)

Your support allows me to keep this package free, up-to-date and maintainable. Alternatively, you can **[spread the word!](http://twitter.com/share?text=I%20am%20using%20this%20cool%20PHP%20package&url=https://github.com%2FLaragear%2FWebAuthn&hashtags=PHP,Laravel)**

## Requirements

* PHP 8.1 or better
* Laravel 9.x

## Installation

Set up Composer and require it into your project:

```shell
composer require laragear/surreal
```

> **Warning**
> Features marked as "planned" are not ready. This documentation puts them as placeholder for tentative implementation.

## Configuration

You may set your DB connection in Laravel by creating a new database entry in your `config/databases.php`. You can copy-paste this example array into the `connections` key:

```php
'surreal' => [
    'driver' => 'surreal',
    'url' => env('DATABASE_URL', 'ws://localhost:8000/rpc'),
    'ns' => env('DB_NAMESPACE', 'forge'),
    'db' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', 'forge'),
],
```

As you can guess, Laragear Surreal uses the [JSON-RPC](https://www.jsonrpc.org/) (WebSockets) endpoint thanks to [amphp v3](https://github.com/amphp/websocket-client).

> **Note**
> You may start SurrealDB matching the configuration in your terminal using `forge` in your local development:
>
> ```shell
> surreal start --log debug --user forge --pass forge
> ```


Add SurrealServiceProvider to providers array in `config\app.php` 

```php
'providers' => [
    ...
    Laragear\Surreal\SurrealServiceProvider::class,
    ...
]
```

### WebSockets

Since PHP is a single thread engine, a single connection is made for each application instance and, once the app terminates, the connection is closed. During the app lifetime, multiple queries are executed in SurrealDB within a single connection.

If you're using [Laravel Octane](https://laravel.com/docs/9.x/octane) or similar, the connection is severed once the instance is terminated, not when the app lifecycle ends, which avoids the connection overhead.

### Read-only connection

Currently, Laragear Surreal doesn't support using a different connection for read and another for write. You're encouraged to:

- use a scoped user with only read operations.
- start SurrealDB on Distributed Mode to leverage persistence and performance, which makes the usage of different connections unnecessary.

## Migrations

SurrealDB is meant to be used as a schemaless document store, like NoSQL or Redis, but you may enforce a schema over a particular _table_. While **there is no need to create migrations** to store and retrieve data, you may want your data to strictly comply with a table schema or [SurrealDB's _strict mode_](https://surrealdb.com/docs/cli/start).

```shell
surreal start --log debug --user forge --pass forge --strict
```

SurrealDB v1.0 [supports](https://github.com/surrealdb/surrealdb/blob/45e1a9adce0d63221e3b6b124d5774e4f7aed73f/lib/src/sql/kind.rs#L37-L55) the following kinds of data models.

| Data Model                | Description                                             |
|---------------------------|---------------------------------------------------------|
| `any($name)`              | Any value type, leaving the cast at query time or app   |
| `array($name)`            | Ordered lists with any depth or value types             |
| `boolean($name)`          | Both `true` or `false`                                  |
| `datetime($name)`         | Any date and time representation                        |
| `decimal($name)`          | String representation of a decimal number               |
| `duration($name)`         | Duration (interval) of time                             |
| `float($name)`            | Double precision (IEEE 754-2008) floating point number  |
| `integer($name)`          | A 64-bit integer                                        |
| `number($name)`           | Auto-detected numeric value, like an integer or a float |
| `object($name)`           | Unordered list with keys and any depth or value type    |
| `record($name, ...table)` | A list of accepted record links separated by comma      |
| `geoJson($name, ...type)` | A list of accepted GeoJSON types separated by comma     |

All of these data models can be `null`. It's up to the application to set a value or not.

To see what's like in action, let's make a schema to store articles. The table will automatically reserve `id` for the [primary key](#primary-keys).

```php
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::create('article', function (Blueprint $table) {
    $table->string('title');
    $table->any('slug')->default('<future> { string::slug(title) }')
    $table->string('body');
    $table->array('tags');
    $table->record('tags.*.tag', Tag::class); // Allow each "tags" member to be linked to a Tag.
    $table->record('author', User::class); // Allow the "user" attribute to be linked to the author.
    $table->softDeletes();
    $table->timestamps();
});
```

```sql
DEFINE TABLE article SCHEMAFULL;
DEFINE FIELD title ON article TYPE string;
DEFINE FIELD slug ON article TYPE any VALUE <future> { string::slug(title) };
DEFINE FIELD body ON article TYPE string;
DEFINE FIELD tags ON article TYPE array;
DEFINE FIELD tags.*.tag ON article TYPE record(tag);
DEFINE FIELD user ON article TYPE record(user);
DEFINE FIELD deleted_at ON article TYPE datetime;
DEFINE FIELD updated_at ON article TYPE datetime;
DEFINE FIELD created_at ON article TYPE datetime;
```

> **Warning**
> Durations in Surreal don't support months, and **are not** ISO 8601 compatible. You may use any `DateInterval` or `CarbonInterval` instance without months, or save an ISO 8601 string and apply the interval in your app.

### Primary Keys

All tables in SurrealDB come with a primary key, which is set as `id` in the record itself — this is not negotiable. There is no need to add a primary key, and doing so will return an error. 

When creating records, the primary key will be set using `table:id` notation. If not set by yourself, SurrealDB creates it as a 20-character random string.

```php
use Illuminate\Support\Facades\DB;

DB::connection('surreal')->table('article')->insert([
   'title' => 'My vacations in Italy',
   'body' => '...',
   'tags' => null,
   'user' => null
])
```

```json
{
    "id": "article:2n5xte3rxl4emiwhgs15",
    "title": "My vacations in Italy",
    "body": "...",
    "tags": null,
    "user": null
}
```

Alternatively, you can create a record with its ID directly by using `id()`;

```php
use Illuminate\Support\Facades\DB;

DB::connection('surreal')->id('article:1')->insert([
   'title' => 'My vacations in Italy',
   // ...
])
```

```json
{
    "id": "article:1",
    "...": "..."
}
```

> **Note**
> Currently, SurrealDB doesn't support sequential integers, but is _planned_ by SurrealDB.

### Database assertions (planned)

Fields on tables support assertions at query-time when a value is inserted or updated. These assertions allow the query to fail completely if the value is not what is expected.

To use assertions, simply use `assert()` with the raw assertion. The value to insert into the data model is `$value`, and it should return `true` to allow the whole row being persisted.

For example, you may define the field `email` and assert that the value is an email with `is::email($value)`.

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::create('user', function (Blueprint $table) {
    // ...

    $table->string('email')->assert('is::email($value)');
    $table->integer('age')->assert('$value > 18');
});

// DEFINE TABLE user SCHEMAFULL;
// DEFINE FIELD email ON user TYPE string ASSERT is::email($value);
// DEFINE FIELD age ON user TYPE integer ASSERT $value > 18;
```

### JSON

If you need to operate over JSON, like over array lengths or object values, you should set the column using `any()`, `object()` or `array()` instead of `json()` or `jsonb()`. The JSON-related column types will create a string rather than accessible JSON, and will be considered as a string for all matters.

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::create('player', function (Blueprint $table) {
    // ...

    $table->any('teams');
    $table->array('positions');
    $table->object('stats');
    $table->json('plain_text');
});
```

```sql
DEFINE TABLE player SCHEMAFULL;
DEFINE FIELD teams ON player TYPE any;
DEFINE FIELD positions ON player TYPE array;
DEFINE FIELD stats ON player TYPE object;
DEFINE FIELD plain_text ON player TYPE string;
```

> **Warning**
> Because of the above, all JSON operations, like `whereJsonContains()`, are unsupported because there is no need to.

## Basic Operations

This database driver should work with INSERT, SELECT, UPDATE, and DELETE operations, which are standard in SQL and SurrealDB understands.

### Select

Selects can proceed as normal. SurrealDB extends the normal `SELECT` clause with a few goodies to make queries more convenient.

#### Selecting by a primary key

You can use `find()` directly on a query builder with the record ID if your default driver is `surreal`. 

```php
use Illuminate\Support\Facades\DB;

$article = DB::find('article:2n5xte3rxl4emiwhgs15', ['*']);
// SELECT * FROM article:2n5xte3rxl4emiwhgs15
```

#### Fetch

You can fetch related records using the `fetch` method and the attributes that contain the relations. This saves multiple queries into one.

```php
use Illuminate\Support\Facades\DB;

// Get his account, and the all the users for the given account. 
DB::table('user:tobie')->fetch('account', 'account.users')->first();

// SELECT * FROM user:tobie FETCH account, account.users
```

```json
{
    "id": "user:tobie",
    "name": "Tobie",
    "account": {
        "id": "account:family",
        "users": [
            {
                "id": "user:tobie",
                "name": "Tobie"
            },
            {
                "id": "user:ana",
                "name": "Ana"
            }
        ]
    }
}
```

Since `fetch()` returns **all** the attributes of the fetched record, you may use `select()` to filter down the related records attributes.

```php
use Illuminate\Support\Facades\DB;

// Get his account, and the all the users for the given account. 
DB::table('user:tobie')->fetch('account', 'account.users')->first([
    'user.name', 'user.age', 'user.account.expiration' , 'user.account.users.*.name'
]);

// SELECT user.name, user.age, user.account.expiration, user.account.users.*.name
//     FROM user:tobie
//     FETCH account, account.users
```

#### Joins

SurrealDB doesn't have supports for JOIN clauses because there is no need. Records can be related to others from the ground up by using their ID as value.

#### Distinct

SurrealDB doesn't support DISTINCT clauses, mainly because GROUP BY offers the same results and is more flexible to work with.

#### Having

SurrealDB doesn't support HAVING clauses. You can create sub-queries almost anywhere to suit your needs.   

### Insert

A noticeable change from the default `insert()` method of the builder is that it will always return the whole record final state instead of just a boolean.

```php
use Illuminate\Support\Facades\DB;

$article = DB::id('article:trip-to-italy')->insert([
   'title' => 'My vacations in Italy',
   'body' => '...',
   'tags' => null,
   'user' => null
]);
```

```json
{
    "id": "article:trip-to-italy",
    "title": "My vacations in Italy",
    "body": "...",
    "tags": null,
    "user": null
}
```

> Inserts don't support [return](#returns), [timeout](#timeouts) or [parallel](#parallel) flags.

### Create

To create a single record into the database, you can use `create()`. It works the same as [`insert()`](#insert), but it supports [return](#returns), [timeout](#timeouts) and [parallel](#parallel) flags.

```php
use Illuminate\Support\Facades\DB;

$article = DB::id('article:trip-to-italy')->returnNone()->create([
   'title' => 'My vacations in Italy',
   'body' => '...',
   'tags' => null,
   'user' => null
]);
```

### Updates

Same case as creation, the whole updated record is returned.

```php
use Illuminate\Support\Facades\DB;

$article = DB::id('article:trip-to-italy')->returnNone()->create([
   'title' => 'My vacations in Italy',
   'body' => '...',
   'tags' => null,
   'user' => null
]);
```

You can also _upsert_ a record.

```php
use Illuminate\Support\Facades\DB;

$article = DB::id('article:trip-to-italy')->upsert([
   'title' => 'My vacations in Italy',
   'body' => '...',
   'tags' => null,
   'user' => null
], 'id', ['user' => 'user:1']);
```

> **Note**
> Upsert in SurrealDB is only done when the primary key is duplicated.

### Deletes

Deletes work as expected, but with the added possibility of returning the deleted record.

```php
use Illuminate\Support\Facades\DB;

$deleted = DB::id('article:trip-to-italy')->return('before')->delete();
```

### Returns

All operations return the whole records affected, except for deletions, which is very useful to know the final state of each record after a change, but it may consume too much memory when left unchecked.

For all write operations, you can use `returnNone()` to avoid SurrealDB returning the data affected. For example, we can create a new article and not receive the new state from the database.

```php
use Illuminate\Support\Facades\DB;

$none = DB::table('article:trip-to-italy')->returnNone()->insert([
   'title' => 'My vacations in Italy',
   'body' => '...',
   'tags' => null,
   'user' => null
]);
```

You can also use multiple `return` types supported by SurrealDB:

```php
use Illuminate\Support\Facades\DB;

// Don't return anything
DB::table('article:trip-to-italy')->return('none')->insert([/** ... */]);

// Return only the attributes that changed.
$diff = DB::table('article:trip-to-italy')->return('diff')->update([/** ... */]);

// Return only the record before it changed.
$before = DB::table('article:trip-to-italy')->return('before')->update([/** ... */]);

// Return the record after it changed.
$after = DB::table('article:trip-to-italy')->return('after')->update([/** ... */]);

// Return some fields of the updated record.
$some = DB::table('article:trip-to-italy')->return(['title', 'body'])->update([/** ... */]);
```

### Timeouts

Timeout allows to kill entire queries that may take too much to process, like large updates or massive deletions. Setting a timeout treats the operation as a transaction, and it will be rolled back if it exceeds the defined duration.

Just use `timeout()` with the number of seconds to limit the operation execution.

```php
use Illuminate\Support\Facades\DB;

// Cancel everything if the update takes more than 5 seconds.
DB::table('user')->timeout(5)->update([
    'age' => 24
]);
// UPDATE user CONTENT {
//   age: 24
// } TIMEOUT 5s
```

### Parallel

If you're confident that a record interconnected with others can be retrieved faster, use the `parallel()` method, which signals SurrealDB to [parallelize the retrieval of edges](https://surrealdb.com/docs/surrealql/statements/select).

```php
use Illuminate\Support\Facades\DB;

DB::table('user:tobie')->parallel()->first('->purchased->product<-purchased<-person->purchased->product.*');
// SELECT ->purchased->product<-purchased<-person->purchased->product.* FROM user:tobie PARALLEL
```

Parallel operations can also be done for updating, inserting, creating, deleting and relating.

> **Note**
> Parallel operations performance are left to SurrealDB.

## Casting (planned)

You can manually [cast a value](https://surrealdb.com/docs/surrealql/datamodel/casting) from and to the record using special Casting classes. The casting works at database level, before JSON encoding/decoding.

```php
use Illuminate\Support\Facades\DB;
use Laragear\Surreal\Query\Cast;

DB::table('user:tobie')->get([
    '*', Cast::bool('registered_at', 'can_see'),
]);

// SELECT *, <bool> registered_at AS can_see FROM user:tobie
//
// {
//     "id": "user:tobie",
//     "name": "John",
//     "registered_at": "2020-01-01 19:30:35",
//     "can_see": true,
// }
```

### Futures (planned)

Futures are properties that are computed **only** when the attributes are returned from a query. You may think of them as _embedded queries_ inside a record attribute.

To create a Future, simple use `Future::be()` with the raw query to execute.

```php
use Illuminate\Support\Facades\DB;
use Laragear\Surreal\Query\Future;

DB::table('person')->insert([
    'name' => 'Jason',
    'friends' => ['person:tobie', 'person:jaimie']
    'adult_friends' => Future::be('friends[WHERE age > $?].name', [18])
});
```

```sql
CREATE person CONTENT {
    "name": 'Jason',
    "friends": [person:tobie, person:jaime],
    "adult_friends": <future> { friends[WHERE age > 18].name }
}
```

In the example above, the `adults_friends` will always return a list of names from the `friends` list of the record that are over 18 old.

You may also create Future from queries by using `asFuture()` instead of executing it.

```php
use Illuminate\Support\Facades\DB;
use Laragear\Surreal\Query\Future;

DB::table('article')->insert([
    'title' => 'Great places to visit',
    'body' => 'Italy, Spain, and London',
    'category' => 'trips',
    'related_articles' => DB::from('article')->where('category', 'trips')->latest()->limit(3)->asFuture()
});
```

```sql
CREATE article CONTENT {
    "title": 'Great places to visit',
    "body": 'Italy, Spain, and London',
    "category": 'trips',
    "related_articles": <future> { SELECT * FROM article WHERE category = 'trips' ORDER BY created_at LIMIT 3 }
};
```

### Geometries (planned)

You can conveniently create GeoJSON objects using the `Geometry` class instance. All geometry types in SurrealDB are supported.

```php
use Illuminate\Support\Facades\DB;
use Laragear\Surreal\Types\Geometry;

DB::table('address')->insert([
    'title' => 'My home',
    'location' => Geometry::point(-36.02010, 146.42279),
});
```

## Variables (planned)

To store a parameter for using in the next query, use `let()` with the name and value of the variables to pass, or an array. To reference the key, append `$` to it.

```php
use Illuminate\Support\Facades\DB;

DB::table('person')->let([
    'name' => 'tobie',
    'adults_friends' => DB::table('person')->where('age', '>', 18),
])->insert([
    'name' => '$name',
    'friends' => '$adults_friends',
]);
```

```sql
LET $name = "tobie";
LET $adults = (SELECT * FROM person WHERE age > 18);

CREATE person CONTENT {
    "name": $name,
    "friends": $adult_friends
}
```

> **Warning**
> Always use named variables instead of numbers. Laragear Surreal uses `$1` type variables for the query bindings.

### Async queries (planned)

Laragear Surreal driver support executing a query without waiting for the results until later in your code, which can yield massive performance improvements. Simply use `async()` in the query you want to execute, which will wrap the operation into a promise that you can resolve later.

Since Async Queries don't wait for the result, you can use them to one-off inserting data.

```php
use Illuminate\Support\Facades\DB;
use Laragear\Surreal\Query\Future;

DB::table('user')
    ->where('age', '>', 18)
    ->async()
    ->returnNone()
    ->update(['is_adult' => Future::be('age > 18')]);
```

Also, you can use to warm-up queries that may be taxing to retrieve with `cursor()`.

```php
use Illuminate\Support\Facades\DB;
use Laragear\Surreal\Query\Future;

$popularArticles = DB::table('article')->has('comments', '>', 100)->cursor();

// Later in your code...

foreach ($popularArticles as $article) {
    // ...
}
```

> **Warning**
> The async response from SurrealDB is not resolved until requested, and that includes errors.

### Functions

Functions are procedures to execute at query time. You can use [any function available](https://surrealdb.com/docs/surrealql/functions) in SurrealDB with the `Func` object in your query.

```php
use Illuminate\Support\Facades\DB;
use Laragear\Surreal\Query\Func;

$http = Func::http()->get('https://supermarket.com/api', [
    'x-my-header': 'some unique string'
]);

DB::table($http)->get();
```

```sql
SELECT * FROM http::get('https://supermarket.com/api', {
    'x-my-header': 'some unique string'
})
```

Functions work only at root level. When using functions on nested items, like an array or an object, these will be cast into raw SurrealSQL.

```php
use Illuminate\Support\Facades\DB;
use Laragear\Surreal\Query\Func;

DB::id('user:john')->create([
    'name' => 'john',
    'email' => 'john@app.com',
    'is_admin' => Func::str()->endsWith('email', '@app.com'),
    'favorites' => [
        'colors' => Func::rand()->enum('blue', 'red'),
        'numbers' => Func::rand()->enum(3, 5, 7)
    ]
]);
```

```sql
CREATE user:john CONTENT {
    'name' => 'john',
    'email' => 'john@app.com',
    'is_admin' => string::endsWith("email", "@app.com"),
    'favorites' => [
        'colors' => rand::enum(blue, red),
        'numbers' => rand::enum(3, 5, 7)
    ]
}
```

> **Danger**
> Functions, by all means, are considered **raw expressions**. Do not use them with user generated content, as you may risk your application to SQL Injection attacks.

### Javascript Functions

You can also use `Func::js()` to execute ES2020-compliant javascript in any part of the query by just setting the function body.

```php
use Illuminate\Support\Facades\DB;
use Laragear\Surreal\Query\Func;

DB::table('something')->insert([
    'scores' => Func::js('return [1,2,3].map(v => v * 10)')
]);
```

```sql
INSERT something CONTENT {
    scores: function () { 
        return [1,2,3].map(v => v * 10)
    }
}
```

> **Note**
> Script functions are useful features, but try not to abuse them because they can be tricky to debug. You're always a browser away to test a script.

### Multiple Queries (planned)

SurrealDB allows for multiple queries run through a single statement.

For example, you may request one user from the database, and the latest three articles from the `article` table. Instead of running each query separately, you may use the `pool` method, which will execute all queries in a single request and return the result of each one.

```php
use Illuminate\Support\Facades\DB;

$queries = DB::connection('surreal')->pool(fn ($surreal) => [
    $surreal->as('user')->from('user:1')->first(),
    $surreal->user('articles')->table('article')->where('author', 'user:1')->latest()->limit(3)->get(),
]);

return [
    $queries['user'], 
    $queries['articles']
];
```

The queries are resolved synchronously. Once all the queries are returned, you will be able to access to them.

## Relationships

SurrealDB breaks the mold on record relationships. If you come from Laravel, you will be pleased to know that relationships come out-of-the-box with SurrealDB: polymorphism is embedded, there is no pivot tables, and Graph Edges are preferred. Let's explain each one.

### Polymorphism

A record can be related to another record through its ID. It doesn't matter if the ID is a number, UUID or a random string. This effectively relates a record to any other record on the database.

```php
use Illuminate\Support\Facades\DB;

DB::table('user')->insert([
    'id' => 'user:1',
    'name' => 'John',
    'favorite' => 'color:red'
]);

DB::table('user')->insert([
    'id' => 'user:2',
    'name' => 'Maria',
    'favorite' => 'team:364gp0m97rv1ynrxphos'
]);
```

You may define an attribute to abide to only a number of given record types on [migrations](#migrations-planned);

### Belongs to Many without pivots

A record can contain an array of related records ID, or an array of objects with related records ID. There is no need to create pivot tables, let alone set up pivot data for each related record.

```php
use Illuminate\Support\Facades\DB;

DB::table('user')->insert([
    'id' => 'user:1',
    'name' => 'John',
    'favorite_things' => [
        'color:red',
        'team:364gp0m97rv1ynrxphos'
    ],
]);

DB::table('article')->insert([
    'id' => 'article:my-trip-to-italy',
    'title' => 'My trip to Italy',
    'tags' => [
        ['tag' => 'tag:vacation', 'is_primary' => true],
        ['tag' => 'tag:europe', 'is_primary' => false],
    ],
]);

$article = DB::table('article')->where('id', 'article:my-trip-to-italy')->fetch('tags.*.tag')->first();
```

One drawback is that pivot data only resides on the origin, or the "child" record. To make this data _shared_ between both, you may use Graph Edges.

### Graph Edges

You may consider Graph Edges as one-way pivot records. A Graph Edge _relates_ one record to another record, which allows for infinite traversal, keeping data that relates to the far relation relevant to only the origin relation, but accessible to both by _switching directions_.

#### Relating records

Using the Query Builder, you can start a _relate_ operation using `relatedTo()` and executing the statement with `through()`. You can also use fluent methods to name the Graph Edge dynamically.

```php
use Illuminate\Support\Facades\DB;

DB::id('user:tobie')->relateTo('user:taylor')->through('knows', ['id' => 1, 'family' => false]);

DB::id('person:1')->relateTo('product:2')->bought(['through' => 'stripe']);
```

```sql
RELATE user:tobie->knows->user:taylor CONTENT {
    id: 1,
    family: false
};

RELATE person:1->bought->product:2 CONTENT {
    through: "stripe"
};
```

### Retrieving Graph Edges

Retrieving the graph edges can be done using a normal `select()` and the direction of the edges. Since Graph Edges are treated as attributes keys, you should ensure that you want to retrieve all or some attributes from these graphs. These relations are _added_ to the select query, and returning as an array of many parents or children.

```php
use Illuminate\Support\Facades\DB;

// Retrieve the user, the buying data, and all the products bought.
DB::id('user:tobie')->select('*', '->bought.*', '->bought->product.*')->first();

// Retrieve the product bought, and all the users who bought it
DB::id('product:teddy-bear')->select('*', '<-bought.*', '<-bought<-user.*')->first();
```

```sql
SELECT *, ->bought.*, ->bought->product.* FROM user:tobie

SELECT *, <-bought.*, <-bought<-product.* FROM product:teddy-bear
```

For more powerful queries, you can use `related()`. The easiest way to travel Graph Edges is using `from()` and `to()` methods, or use the property or method appended with `from` and `to`, respectively.

```php
use Illuminate\Support\Facades\DB;

DB::id('user:tobie')->related()->to('bought')->to('product')->get();

DB::id('product:1')->related()->fromBought->fromUser()->get();
```

```sql
SELECT *, ->bought->product.*, FROM user:tobie

SELECT *, <-bought<-user.*, FROM product:1
```

We can also use `related()` to filter the results through a query on each segment. For example, we can filter the 5 most recent products bought through Stripe.

```php
use Illuminate\Support\Facades\DB;

// Retrieve the last 5 products bought by this user using Stripe.
DB::id('user:tobie')->related()
    ->toBought(fn($query) => $query->where('through', 'stripe'))
    ->toProduct()
    ->latest()->limit(5)->get();
```

```sql
SELECT *, ->(bought WHERE through = "stripe")->product.* 
    FROM user:tobie
    ORDER BY `created_at`
    LIMIT 5
```

The `related()` function allows to use an array of Graph Edges to traverse, making possible to retrieve multiple relations in one statement.

```php
use Illuminate\Support\Facades\DB;

DB::id('user:tobie')->related(
    ['->bought' => fn($query) => $query->where('through', 'stripe'), '->product'],
    ['<-taxes', '<-invoices']
)->get();
```

```sql
SELECT *, ->(bought WHERE through = "stripe")->product.*, <-taxes<-invoices.* 
    FROM user:tobie
```

> **Warning**
> Currently SurrealDB doesn't support SELECT statements on edge subqueries. When setting a subquery, SurrealDB will append `.*` to retrieve all attributes of the far related record.

## Bindings

Laravel uses the `?` placeholder to signal bindings. While this _can_ work with SurrealDB, it messes with operators like `?=` and `?~`. For that reason, the placeholder for SurrealDB queries is **`$?`**, and default bindings are set with numbers.

When creating a query with bindings manually, you can use `$?` place the binding to replace.

```php
use Illuminate\Support\Facades\DB;

DB::connection('surreal')->create('CREATE user:1 SET email = $?', ['john@email.com']);
```

## Laravel Octane Compatibility

* There are no singletons using a stale application instance.
* There are no singletons using a stale config instance.
* There are no singletons using a stale request instance.
* There are no static properties written during a request.

There should be no problems using this package with Laravel Octane.

## Roadmap

For this to work _elegantly_ with Laravel, there is still a few tasks remaining to make this driver stable. You can check it out on the [Project Roadmap](https://github.com/orgs/Laragear/projects/1).

If you're interested in a stable release and fuel development, you may [become a sponsor](https://github.com/sponsors/DarkGhostHunter/).

## Security

If you discover any security related issues, please email darkghosthunter@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

Laravel is a Trademark of Taylor Otwell. Copyright © 2011-2022 Laravel LLC.
SurrealDB is a Trademark of SurrealDB. Copyright © 2011-2022 SurrealDB Ltd.
