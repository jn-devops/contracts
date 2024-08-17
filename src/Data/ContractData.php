<?php

namespace Homeful\Contracts\Data;

use Homeful\Mortgage\Data\MortgageData;
use Homeful\Contracts\Models\Contract;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class ContractData extends Data
{
    public function __construct(
        public ?string $reference_code,
        public MortgageData $mortgage,
        public string $state,
        public ?Carbon $consulted_at,
        public ?Carbon $availed_at,
        public ?Carbon $verified_at,
        public ?Carbon $onboarded_at,
        public ?Carbon $paid_at,
        public ?Carbon $approved_at,
        public ?Carbon $disapproved_at,
        public ?Carbon $overridden_at,
        public ?Carbon $cancelled_at,
        public bool $consulted,
        public bool $availed,
        public bool $verified,
        public bool $onboarded,
        public bool $paid,
        public bool $approved,
        public bool $disapproved,
        public bool $overridden,
        public bool $cancelled
    ){}

    public static function fromObject(Contract $contract): ContractData
    {
        return new self(
            reference_code: $contract->reference_code,
            mortgage: MortgageData::fromObject($contract->mortgage),
            state: $contract->state->getValue(),
            consulted_at: $contract->consulted_at,
            availed_at: $contract->availed_at,
            verified_at: $contract->verified_at,
            onboarded_at: $contract->verified_at,
            paid_at: $contract->paid_at,
            approved_at: $contract->approved_at,
            disapproved_at: $contract->disapproved_at,
            overridden_at: $contract->overridden_at,
            cancelled_at: $contract->cancelled_at,
            consulted: $contract->consulted,
            availed: $contract->availed,
            verified: $contract->verified,
            onboarded: $contract->onboarded,
            paid: $contract->onboarded,
            approved: $contract->approved,
            disapproved: $contract->disapproved,
            overridden: $contract->overridden,
            cancelled: $contract->cancelled
        );
    }
}
