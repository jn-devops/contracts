<?php

namespace Homeful\Contracts\States;

class Validated extends ContractState
{
    public function name(): string
    {
        return 'validated';
    }
}
