<?php

namespace Homeful\Contracts\States;

class Paid extends ContractState
{
    public function name(): string
    {
        return 'paid';
    }
}
