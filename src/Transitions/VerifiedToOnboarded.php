<?php

namespace Homeful\Contracts\Transitions;

use Homeful\References\Models\Reference;
use Homeful\Contracts\States\Onboarded;
use Homeful\Contracts\Models\Contract;
use Illuminate\Support\Arr;

class VerifiedToOnboarded extends ContractTransition
{
    protected string $qr_code_url;

    public function __construct(Contract $contract, string $qr_code_url, Reference|string $reference = null)
    {
        parent::__construct($contract, $reference);

        $this->qr_code_url = $qr_code_url;
    }

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
                $this->contract->customer->notify(new $notification_class($data, $this->getQRCodeUrl()));
            }
        }
    }

    public function getQRCodeUrl(): string
    {
        return $this->qr_code_url;
    }
}
