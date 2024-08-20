<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Approved;

class QualifiedToApproved extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Approved($this->contract);
        $this->contract->approved = true;
        $this->contract->save();

        return $this->contract;
    }
}
