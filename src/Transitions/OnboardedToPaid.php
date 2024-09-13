<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Notifications\Notifications\PostPaymentBuyerNotification;
use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Paid;

class OnboardedToPaid extends ContractTransition
{
    protected string $notification_class = PostPaymentBuyerNotification::class;

    public function handle(): Contract
    {
        $this->contract->state = new Paid($this->contract);
        $this->contract->paid = true;
        $this->contract->save();
        $this->notify();

        return $this->contract;
    }
}
