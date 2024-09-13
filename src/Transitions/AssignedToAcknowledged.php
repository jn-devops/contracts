<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Acknowledged;
use Homeful\Contracts\States\Cancelled;
use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Idled;

class AssignedToAcknowledged extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Acknowledged($this->contract);
        $this->contract->acknowledged = true;
        $this->contract->save();

        return parent::handle();
    }
}
