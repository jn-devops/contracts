<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Qualified;
use Homeful\Contracts\Models\Contract;

class PrequalifiedToQualified extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Qualified($this->contract);
        $this->contract->qualified = true;
        $this->contract->save();

        return parent::handle();
    }
}
