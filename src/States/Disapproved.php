<?php

namespace Homeful\Contracts\States;

class Disapproved extends ContractState
{
    public function name(): string
    {
        return 'disapproved';
    }
}
