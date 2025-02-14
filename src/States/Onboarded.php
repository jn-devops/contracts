<?php

namespace Homeful\Contracts\States;

class Onboarded extends ContractState
{
    public function name(): string
    {
        return 'onboarded';
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
