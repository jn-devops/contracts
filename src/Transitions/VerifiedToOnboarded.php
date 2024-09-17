<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Onboarded;
use Homeful\Contracts\Models\Contract;
use Illuminate\Support\Arr;

class VerifiedToOnboarded extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Onboarded($this->contract);
        $this->contract->onboarded = true;
        $this->contract->save();

        return parent::handle();
    }

    public function notify(): void
    {
        if ($data = $this->getReferenceData()) {
            $config = (config('contracts.notifications'));
            $transition_class = get_class($this);
            $notification_classes = Arr::get($config, $transition_class, []);
            foreach ($notification_classes as $notification_class) {
                $this->contract->customer->notify(new $notification_class($data, 'https://pay.wepayez.com/qrcode/code?uuid=18c4185824a70bc58e8f569c0c671b251'));
            }
        }
    }
}
