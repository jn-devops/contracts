<?php

namespace Homeful\Contracts\States;

class Overridden extends ContractState
{
    public function name(): string
    {
        return 'overridden';
    }
}
