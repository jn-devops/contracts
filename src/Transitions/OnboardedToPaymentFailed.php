<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\PaymentFailed;
use Homeful\Contracts\Models\Contract;

class OnboardedToPaymentFailed extends ContractTransition
{
    /**
     * @return Contract
     */
    public function handle(): Contract
    {
        $this->contract->state = new PaymentFailed($this->contract);
        $this->contract->payment_failed = true;
        $this->contract->save();

        return parent::handle();
    }
}
