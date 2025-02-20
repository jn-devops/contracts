<?php

namespace Homeful\Contracts\States;

class PaymentFailed extends ContractState
{
    public function name(): string
    {
        return 'payment failed';
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
