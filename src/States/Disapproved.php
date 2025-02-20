<?php

namespace Homeful\Contracts\States;

class Disapproved extends ContractState
{
    public function name(): string
    {
        return 'disapproved';
    }

    public function color(): string
    {
        return 'danger';
    }
    public function icon(): string
    {
        return 'heroicon-m-arrow-path';
    }
}
