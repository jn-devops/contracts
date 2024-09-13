<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\NotQualified;
use Homeful\Contracts\States\Qualified;
use Homeful\Contracts\Models\Contract;

class PaidToNotQualified extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new NotQualified($this->contract);
        $this->contract->not_qualified = true;
        $this->contract->save();

        return parent::handle();
    }
}
