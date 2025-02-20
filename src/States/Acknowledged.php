<?php

namespace Homeful\Contracts\States;

class Acknowledged extends ContractState
{
    public function name(): string
    {
        return 'acknowledged';
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
