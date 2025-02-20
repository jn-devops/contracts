<?php

namespace Homeful\Contracts\States;

class Availed extends ContractState
{
    public function name(): string
    {
        return 'availed';
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
