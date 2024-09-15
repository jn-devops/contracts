<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Assigned;
use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Validated;

class OverriddenToValidated extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Validated($this->contract);
        $this->contract->validated = true;
        $this->contract->save();

        return parent::handle();
    }
}
