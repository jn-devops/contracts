<?php

namespace Homeful\Contracts\States;

class Qualified extends ContractState
{
    public function name(): string
    {
        return 'qualified';
    }
}
