<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\Models\Contract;
use Spatie\ModelStates\Transition;

abstract class ContractTransition extends Transition
{
    protected Contract $contract;

    /**
     * @param Contract $contract
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    abstract public function handle(): Contract;
}
