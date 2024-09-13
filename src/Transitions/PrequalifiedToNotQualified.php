<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\NotQualified;
use Homeful\Contracts\States\Paid;

class PrequalifiedToNotQualified extends ContractTransition
{
    /**
     * @return Contract
     */
    public function handle(): Contract
    {
        $this->contract->state = new NotQualified($this->contract);
        $this->contract->not_qualified = true;
        $this->contract->save();

        return parent::handle();
    }
}
