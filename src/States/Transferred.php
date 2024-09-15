<?php

namespace Homeful\Contracts\States;

class Transferred extends ContractState
{
    public function name(): string
    {
        return 'transferred';
    }
}
