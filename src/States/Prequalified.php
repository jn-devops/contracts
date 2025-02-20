<?php

namespace Homeful\Contracts\States;

class Prequalified extends ContractState
{
    public function name(): string
    {
        return 'pre-qualified';
    }
    public function color(): string
    {
        return 'warning';
    }
    public function icon(): string
    {
        return 'heroicon-m-arrow-path';
    }
}
