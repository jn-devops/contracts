<?php

namespace Homeful\Contracts\States;

use Spatie\ModelStates\Transition;

class Consulted extends ContractState
{
    public function name(): string
    {
        return 'consulted';
    }
}
