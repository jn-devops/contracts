<?php

namespace Homeful\Contracts\States;

class Approved extends ContractState
{
    public function name(): string
    {
        return 'approved';
    }
}
