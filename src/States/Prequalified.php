<?php

namespace Homeful\Contracts\States;

class Prequalified extends ContractState
{
    public function name(): string
    {
        return 'pre-qualified';
    }
}
