<?php

namespace Homeful\Contracts\Transitions;

use Homeful\References\Data\ReferenceData;
use Homeful\References\Models\Reference;
use Homeful\Contracts\Models\Contract;
use Spatie\ModelStates\Transition;
use Illuminate\Support\Arr;

abstract class ContractTransition extends Transition
{
    protected Contract $contract;

    protected Reference $reference;

    protected string $reference_code;

    /**
     * @param Contract $contract
     * @param Reference|string|null $reference
     */
    public function __construct(Contract $contract, Reference|string $reference = null)
    {
        $this->contract = $contract;
        if (null !== $reference)
            if ($reference instanceof Reference) {
                $this->reference = $reference;
                $this->reference_code = $reference->code;
            }
            else {
                $this->reference_code = $reference;
                $this->reference = Reference::where('code', $reference)->firstOrFail();
            }
    }

    public function handle(): Contract
    {
        $this->notify();

        return $this->contract;
    }

    public function getReference(): ?Reference
    {
        return $this->reference ?? null;
    }

    public function getReferenceCode(): ?string
    {
        return $this->reference_code ?? null;
    }

    public function getReferenceData(): ?ReferenceData
    {
        $reference = $this->getReference();

        return $reference ? ReferenceData::fromModel($reference) : null;
    }

    public function notify(): void
    {
        if ($data = $this->getReferenceData()) {
            $config = (config('contracts.notifications'));
            $transition_class = get_class($this);
            $notification_classes = Arr::get($config, $transition_class, []);
            foreach ($notification_classes as $notification_class) {
                $this->contract->customer->notify(new $notification_class($data));
            }
        }
    }
}
