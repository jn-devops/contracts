<?php

namespace Homeful\Contracts\States;

class Cancelled extends ContractState
{
    public function name(): string
    {
        return 'cancelled';
    }
}
