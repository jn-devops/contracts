<?php

namespace Homeful\Contracts\States;

class Acknowledged extends ContractState
{
    public function name(): string
    {
        return 'acknowledged';
    }
}
