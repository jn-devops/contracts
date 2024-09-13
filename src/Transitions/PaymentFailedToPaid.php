<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Paid;

class PaymentFailedToPaid extends ContractTransition
{
    /**
     * @return Contract
     */
    public function handle(): Contract
    {
        $this->contract->state = new Paid($this->contract);
        $this->contract->paid = true;
        $this->contract->save();

        return parent::handle();
    }
}
