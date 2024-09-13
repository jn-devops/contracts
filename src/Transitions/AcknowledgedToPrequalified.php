<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Prequalified;
use Homeful\Contracts\Models\Contract;

class AcknowledgedToPrequalified extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Prequalified($this->contract);
        $this->contract->prequalified = true;
        $this->contract->save();

        return parent::handle();
    }
}
