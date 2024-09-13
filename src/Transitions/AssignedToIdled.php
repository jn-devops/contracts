<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Cancelled;
use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Idled;

class AssignedToIdled extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Idled($this->contract);
        $this->contract->idled = true;
        $this->contract->save();

        return parent::handle();
    }
}
