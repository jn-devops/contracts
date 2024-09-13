<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Notifications\Notifications\PostPaymentBuyerNotification;
use Homeful\References\Data\ReferenceData;
use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Paid;

class OnboardedToPaid extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Paid($this->contract);
        $this->contract->paid = true;
        $this->contract->save();

        if ($reference = $this->getReference()) {
            $data = ReferenceData::fromModel($reference);
            $this->contract->customer->notify(new PostPaymentBuyerNotification($data));
        }

        return $this->contract;
    }
}
