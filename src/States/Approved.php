<?php

namespace Homeful\Contracts\States;

class Approved extends ContractState
{
    public function name(): string
    {
        return 'approved';
    }

    public function color(): string
    {
        return 'info';
    }
    public function icon(): string
    {
        return 'heroicon-m-sparkles';
    }
}
