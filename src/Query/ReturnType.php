<?php

namespace Laragear\Surreal\Query;

enum ReturnType: string
{
    case After = 'after';
    case Before = 'before';
    case Diff = 'diff';
    case Default = 'default';
    case None = 'none';
}
