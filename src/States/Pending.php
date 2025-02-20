<?php

namespace Homeful\Contracts\States;

class Pending extends ContractState
{
    public function name(): string
    {
        return 'pending';
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
