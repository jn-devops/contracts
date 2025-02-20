<?php

namespace Homeful\Contracts\States;

class Overridden extends ContractState
{
    public function name(): string
    {
        return 'overridden';
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
