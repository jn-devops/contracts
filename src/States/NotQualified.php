<?php

namespace Homeful\Contracts\States;

class NotQualified extends ContractState
{
    public function name(): string
    {
        return 'not qualified';
    }
}
