<?php

namespace Homeful\Contracts\States;

class Assigned extends ContractState
{
    public function name(): string
    {
        return 'assigned';
    }
}
