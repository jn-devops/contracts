<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Verified;

class AvailedToVerified extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Verified($this->contract);
        $this->contract->verified = true;
        $this->contract->save();

        return $this->contract;
    }
}
