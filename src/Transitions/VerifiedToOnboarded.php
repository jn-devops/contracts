<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Onboarded;
use Homeful\Contracts\Models\Contract;

class VerifiedToOnboarded extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Onboarded($this->contract);
        $this->contract->onboarded = true;
        $this->contract->save();

        return parent::handle();
    }
}
