<?php

namespace Homeful\Contracts\Transitions;

use Homeful\References\Models\Reference;
use Homeful\Contracts\Models\Contract;
use Spatie\ModelStates\Transition;

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

    abstract public function handle(): Contract;

    public function getReference(): ?Reference
    {
        return $this->reference ?? null;
    }

    public function getReferenceCode(): ?string
    {
        return $this->reference_code ?? null;
    }
}
