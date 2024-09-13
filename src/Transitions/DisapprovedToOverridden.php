<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Overridden;
use Homeful\Contracts\Models\Contract;

class DisapprovedToOverridden extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Overridden($this->contract);
        $this->contract->overridden = true;
        $this->contract->save();

        return parent::handle();
    }
}
