<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Cancelled;
use Homeful\Contracts\Models\Contract;

class ApprovedToCancelled extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Cancelled($this->contract);
        $this->contract->cancelled = true;
        $this->contract->save();

        return $this->contract;
    }
}
