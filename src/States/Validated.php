<?php

namespace Homeful\Contracts\States;

class Validated extends ContractState
{
    public function name(): string
    {
        return 'validated';
    }
    public function color(): string
    {
        return '';
    }
    public function icon(): string
    {
        return '';
    }
}
