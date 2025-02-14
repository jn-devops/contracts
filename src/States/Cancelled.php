<?php

namespace Homeful\Contracts\States;

class Cancelled extends ContractState
{
    public function name(): string
    {
        return 'cancelled';
    }

    public function color(): string
    {
        return 'grey';
    }
    public function icon(): string
    {
        return 'heroicon-m-x-circle';
    }
}
