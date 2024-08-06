<?php

namespace Homeful\Contracts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Homeful\Contracts\Contracts
 */
class Contracts extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Homeful\Contracts\Contracts::class;
    }
}
