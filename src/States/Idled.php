<?php

namespace Homeful\Contracts\States;

class Idled extends ContractState
{
    public function name(): string
    {
        return 'idled';
    }
}
