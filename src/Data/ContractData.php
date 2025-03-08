<?php

namespace Homeful\Contracts\Data;

use Homeful\Contacts\Classes\ContactMetaData;
use Homeful\Properties\Data\PropertyData;
use Homeful\Mortgage\Data\MortgageData;
use Homeful\Contracts\Models\Contract;
use Homeful\Contacts\Data\ContactData;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;
use Homeful\Contracts\Data\LoanTermOptionData;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class ContractData extends Data
{
    public function __construct(
        public ?string $reference_code,
        public ?ContactMetaData $contact,
        public ?PropertyData $property,
        public ?ContactMetaData $customer,
        public ?PropertyData $inventory,
        public ?MortgageData $mortgage,
        public string $state,
        public Carbon|null $consulted_at,
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
        public bool $cancelled,
        public ?LoanTermOptionData $loan_term_option,
        public ?PaymentData $payment,
        public ?SchemalessAttributes $misc,
    ){}

    public static function fromModel(Contract $contract): ContractData
    {
        return new self(
            reference_code: $contract->getAttribute('reference_code'),
            contact: $contract->contact,
            property: $contract->property,
            customer: null == $contract->customer ? null : ContactMetaData::from($contract->customer->toArray()),
//            customer: null == $contract->customer ? null : ContactData::fromModel($contract->customer),
            inventory: null == $contract->inventory ? null : PropertyData::fromModel($contract->inventory),
            mortgage: null == $contract->mortgage ? null : MortgageData::fromObject($contract->mortgage),
            state: $contract->state->name(), //$contract->state->getValue(),
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
            cancelled: $contract->cancelled,
            loan_term_option: $contract->loan_term_option,
            payment: $contract->payment,
            misc: $contract->misc,
        );
    }
}
