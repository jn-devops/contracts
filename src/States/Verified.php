<?php

namespace Homeful\Contracts\States;

class Verified extends ContractState
{
    public function name(): string
    {
        return 'verified';
    }
}
