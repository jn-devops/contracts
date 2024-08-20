<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Disapproved;
use Homeful\Contracts\Models\Contract;

class QualifiedToDisapproved extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Disapproved($this->contract);
        $this->contract->disapproved = true;
        $this->contract->save();

        return $this->contract;
    }
}
