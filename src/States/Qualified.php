<?php

namespace Homeful\Contracts\States;

class Qualified extends ContractState
{
    public function name(): string
    {
        return 'qualified';
    }
    public function color(): string
    {
        return 'success';
    }
    public function icon(): string
    {
        return 'heroicon-m-check-badge';
    }
}
