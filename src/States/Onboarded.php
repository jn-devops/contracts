<?php

namespace Homeful\Contracts\States;

class Onboarded extends ContractState
{
    public function name(): string
    {
        return 'onboarded';
    }
}
