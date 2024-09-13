<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Assigned;
use Homeful\Contracts\Models\Contract;

class PaidToAssigned extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Assigned($this->contract);
        $this->contract->assigned = true;
        $this->contract->save();

        return parent::handle();
    }
}
