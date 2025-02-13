<?php

use Illuminate\Support\Str;
use Homeful\Contracts\Transitions\{
    PendingToConsulted, ConsultedToAvailed, AvailedToVerified, VerifiedToOnboarded, OnboardedToPaid, OnboardedToPaymentFailed,
    PaymentFailedToPaid, PaidToAssigned, AssignedToAcknowledged, AssignedToIdled, IdledToAcknowledged,
    AcknowledgedToPrequalified, PrequalifiedToQualified, PrequalifiedToNotQualified,
    QualifiedToApproved, QualifiedToDisapproved, DisapprovedToOverridden,
    ApprovedToValidated, OverriddenToValidated,
    ValidatedToCancelled
};
use Homeful\Notifications\Notifications\AcknowledgedToPrequalifiedBuyerNotification;
use Homeful\Notifications\Notifications\PrequalifiedToNotQualifiedBuyerNotification;
use Homeful\Notifications\Notifications\OnboardedToPaymentFailedBuyerNotification;
use Homeful\Notifications\Notifications\PrequalifiedToQualifiedBuyerNotification;
use Homeful\Notifications\Notifications\DisapprovedToOverriddenBuyerNotification;
use Homeful\Notifications\Notifications\QualifiedToDisapprovedBuyerNotification;
use Homeful\Notifications\Notifications\AssignedToAcknowledgedBuyerNotification;
use Homeful\Notifications\Notifications\OverriddenToValidatedBuyerNotification;
use Homeful\Notifications\Notifications\OverriddenToCancelledBuyerNotification;
use Homeful\Notifications\Notifications\ValidatedToCancelledBuyerNotification;
use Homeful\Notifications\Notifications\PaymentFailedToPaidBuyerNotification;
use Homeful\Notifications\Notifications\QualifiedToApprovedBuyerNotification;
use Homeful\Notifications\Notifications\VerifiedToOnboardedBuyerNotification;
use Homeful\Notifications\Notifications\ApprovedToValidatedBuyerNotification;
use Homeful\Notifications\Notifications\ApprovedToCancelledBuyerNotification;
use Homeful\Notifications\Notifications\IdledToAcknowledgedBuyerNotification;
use Homeful\Notifications\Notifications\OnboardedToPaidBuyerNotification;
use Homeful\Notifications\Notifications\AssignedToIdledBuyerNotification;
use Homeful\Notifications\Notifications\PaidToAssignedBuyerNotification;

use Homeful\References\Actions\CreateReferenceAction;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Homeful\Properties\Models\Property as Inventory;
use Homeful\Common\Classes\Input as InputFieldName;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Homeful\Contacts\Models\Customer as Contact;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\ModelStates\Events\StateChanged;
use Homeful\Contracts\States\PaymentFailed;
use Homeful\References\Facades\References;
use Homeful\Contracts\States\Prequalified;
use Homeful\Contracts\States\NotQualified;
use Homeful\Contracts\States\Acknowledged;
use Homeful\Properties\Data\PropertyData;
use Homeful\Contracts\States\Disapproved;
use Homeful\Contracts\States\Overridden;
use Homeful\Contracts\Data\ContractData;
use Homeful\References\Models\Reference;
use Homeful\Contracts\States\Validated;
use Homeful\Contracts\States\Qualified;

use Homeful\Contracts\States\Cancelled;
use Homeful\Contracts\States\Onboarded;
use Homeful\Mortgage\Data\MortgageData;
use Homeful\Contacts\Data\ContactData;
use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Approved;
use Homeful\Contracts\States\Verified;
use Homeful\Contracts\States\Assigned;

use Homeful\Contracts\States\Availed;
use Illuminate\Support\Facades\Event;
use Homeful\Products\Models\Product;
use Homeful\Contracts\States\Idled;
use Homeful\Contracts\States\Paid;
use Homeful\Common\Classes\Input;
use Homeful\Borrower\Borrower;
use Homeful\Property\Property;
use Homeful\Mortgage\Mortgage;
use Illuminate\Support\Carbon;

use Homeful\Contracts\States\{Pending, Consulted};
use Homeful\KwYCCheck\Data\CheckinData;
use Homeful\KwYCCheck\Models\Lead;
use Homeful\Common\Classes\Amount;
use Homeful\Contracts\Data\LoanTermOptionData;
use Homeful\Contracts\Data\PaymentData;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    Notification::fake();
    $migration = include 'vendor/jn-devops/contacts/database/migrations/create_contacts_table.php.stub';
    $migration->up();
    $migration = include 'vendor/jn-devops/contacts/database/migrations/update_fields_and_then_add_some_in_contacts_table.php.stub';
    $migration->up();
    $migration = include 'vendor/jn-devops/products/database/migrations/create_products_table.php.stub';
    $migration->up();
    $migration = include 'vendor/jn-devops/properties/database/migrations/create_properties_table.php.stub';
    $migration->up();
    $migration = include 'vendor/jn-devops/properties/database/migrations/d_add_status_to_properties_table.php.stub';
    $migration->up();
    $migration = include 'vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub';
    $migration->up();
    $migration = include 'vendor/frittenkeez/laravel-vouchers/publishes/migrations/2018_06_12_000000_create_voucher_tables.php';
    $migration->up();
    $migration = include 'vendor/homeful/references/database/migrations/create_inputs_table.php.stub';
    $migration->up();
});

dataset('customer', function() {
    return [
        [fn () => Contact::factory()
            ->state(['date_of_birth' => '1999-03-17'])
            ->withEmployment([
                0 => [
                    'type' => 'Primary',
                    'monthly_gross_income' => 60000.0,
                    'current_position' => 'Developer',
                ],
                1 => [
                    'type' => 'Sideline',
                    'monthly_gross_income' => 20000.0,
                    'current_position' => 'Freelancer',
                ]
            ])
            ->withCoBorrowers([
                0 => [
                    'date_of_birth' => '1998-08-12',
                    'employment' => [
                        0 => [
                            'type' => 'Primary',
                            'monthly_gross_income' => 50000.0,
                            'current_position' => 'Engineer',
                        ]
                    ]
                ],
                1 => [
                    'date_of_birth' => '1995-01-24',
                    'employment' => [
                        0 => [
                            'type' => 'Sideline',
                            'monthly_gross_income' => 40000.0,
                            'current_position' => 'Developer',
                        ]
                    ]
                ]
            ])->create()
        ]
    ];
});

dataset('inventory', function() {
    return [
        [fn () => Inventory::factory()->state(function () {$product = Product::factory()->create(['price' => 2500000]); return ['sku' => $product->sku];})->create()]
    ];
});

dataset('params', function() {
    return [
        fn () => [
            Input::PERCENT_DP => 5 / 100,
            Input::PERCENT_MF => 8.5 / 100,
            Input::DP_TERM => 12,
            Input::BP_TERM => 20,
            Input::BP_INTEREST_RATE => 7 / 100,
        ]
    ];
});

it('has a settable id', function() {
    with(Contract::factory()->create(['id' => '8b182ba6-842f-4531-9d38-920e5a904359']), function ($contract) {
        expect($contract->id)->toBe('8b182ba6-842f-4531-9d38-920e5a904359');
    });
});

it('has simple attributes', function () {
    with(Contract::factory()
        ->for(Contact::factory()->state(['date_of_birth' => '1999-03-17']), 'customer')
        ->for(Inventory::factory()->state(function () {$product = Product::factory()->create(['price' => 2500000]); return ['sku' => $product->sku];}), 'inventory')->create(),
        function ($contract) {
            expect($contract->id)->toBeUuid();
            expect($contract->customer)->toBeInstanceOf(Contact::class);
            expect($contract->inventory)->toBeInstanceOf(Inventory::class);
            expect($contract->meta)->toBeInstanceOf(SchemalessAttributes::class);
            expect($contract->percent_down_payment)->toBeFloat();
            expect($contract->percent_miscellaneous_fees)->toBeFloat();
            expect($contract->down_payment_term)->toBeFloat();
            expect($contract->balance_payment_term)->toBeFloat();
            expect($contract->interest_rate)->toBeFloat();
            expect($contract->consulted)->toBeBool();
            expect($contract->availed)->toBeBool();
            expect($contract->verified)->toBeBool();
            expect($contract->onboarded)->toBeBool();
            expect($contract->paid)->toBeBool();
            expect($contract->approved)->toBeBool();
            expect($contract->cancelled)->toBeBool();
            expect($contract->seller_commission_code)->toBeString();
            expect($contract->reference_code)->toBeNull();//there should not be any reference code in contract
        });
});

it('has optional attributes', function(Contact $customer, Inventory $inventory, array $params) {
    with(new Contract, function (Contract $contract) use ($customer, $inventory, $params) {
        $contract->customer = $customer;
        $contract->inventory = $inventory;
        $contract->percent_down_payment = $params[Input::PERCENT_DP];
        $contract->percent_miscellaneous_fees = $params[Input::PERCENT_MF];
        $contract->down_payment_term = $params[Input::DP_TERM];
        $contract->balance_payment_term = $params[Input::BP_TERM];
        $contract->interest_rate = $params[Input::BP_INTEREST_RATE];
        $contract->save();
        expect($contract->seller_commission_code)->toBeNull();
        expect($contract->consulted)->toBeFalse();
        expect($contract->availed)->toBeFalse();
        expect($contract->verified)->toBeFalse();
        expect($contract->onboarded)->toBeFalse();
        expect($contract->paid)->toBeFalse();
        expect($contract->approved)->toBeFalse();
        expect($contract->disapproved)->toBeFalse();
        expect($contract->overridden)->toBeFalse();
        expect($contract->cancelled)->toBeFalse();
        expect($contract)->toBeInstanceOf(Contract::class);
        expect($contract->customer)->toBeInstanceOf(Contact::class);
        expect($contract->inventory)->toBeInstanceOf(Inventory::class);
        expect($contract->mortgage)->toBeInstanceOf(Mortgage::class);
    });

})->with('customer', 'inventory', 'params');

it('can be filled', function(Contact $customer, Inventory $inventory, array $params) {
    $contract = app(Contract::class)->create([
        'customer' => $customer,
        'inventory' => $inventory,
        'percent_down_payment' => $params[Input::PERCENT_DP],
        'percent_miscellaneous_fees' => $params[Input::PERCENT_MF],
        'down_payment_term' => $params[Input::DP_TERM],
        'balance_payment_term' => $params[Input::BP_TERM],
        'interest_rate' => $params[Input::BP_INTEREST_RATE],
    ]);
    expect($contract)->toBeInstanceOf(Contract::class);
    expect($contract->customer)->toBeInstanceOf(Contact::class);
    expect($contract->inventory)->toBeInstanceOf(Inventory::class);
    expect($contract->mortgage)->toBeInstanceOf(Mortgage::class);
})->with('customer', 'inventory', 'params');

it('has a customer relation', function(Contact $customer) {
    with(Contract::factory()->for(factory: $customer, relationship: 'customer')->create(), function (Contract $contract) use ($customer) {
        expect($contract->getAttribute('contact_id'))->toBe($customer->id);
        expect($contract->customer->id)->toBeUuid();
        expect($contract->customer->is($customer))->toBeTrue();
        expect($contract->customer->getBirthDate()->eq('1999-03-17'))->toBeTrue();
        expect($contract->customer->getGrossMonthlyIncome()->inclusive()->compareTo(170000))->toBe(Amount::EQUAL);
    });
})->with( 'customer');

it('can set customer', function(Contact $customer) {
    $contract = new Contract;
    $contract->save();
    expect($contract->customer)->toBeNull();
    $contract->customer = $customer;
    $contract->save();
    expect($contract->customer->is($customer))->toBeTrue();
})->with('customer');

it('has an inventory relation', function (Inventory $inventory) {
    with(Contract::factory()->for(factory: $inventory, relationship: 'inventory')->create(), function (Contract $contract) use ($inventory) {
        expect($contract->inventory->id)->toBe($inventory->id);
        expect($contract->inventory->id)->toBeInt();
        expect($contract->inventory->is($inventory))->toBeTrue();
        expect($contract->inventory->product->getTotalContractPrice()->inclusive()->compareTo(2500000))->toBe(0);

    });
})->with('inventory');

it('can set inventory', function(Inventory $inventory) {
    $contract = new Contract;
    $contract->save();
    expect($contract->inventory)->toBeNull();
    $contract->inventory = $inventory;
    $contract->save();
    expect($contract->inventory->is($inventory))->toBeTrue();
})->with('inventory');

it('can compute mortgage from input attributes', function(Contact $customer, Inventory $inventory, array $params) {
    $contract = new Contract;
    $contract->customer = $customer;
    $contract->inventory = $inventory;
    $contract->percent_down_payment = $params[Input::PERCENT_DP];
    $contract->percent_miscellaneous_fees = $params[Input::PERCENT_MF];
    $contract->down_payment_term = $params[Input::DP_TERM];
    $contract->balance_payment_term = $params[Input::BP_TERM];
    $contract->interest_rate = $params[Input::BP_INTEREST_RATE];
    $contract->save();
    $contract->load('customer', 'inventory');

    $borrower = (new Borrower)->setGrossMonthlyIncome($customer->getGrossMonthlyIncome())->setBirthdate($customer->getBirthdate());
    $property = (new Property)->setTotalContractPrice($inventory->product->getTotalContractPrice())->setAppraisedValue($inventory->product->getAppraisedValue());

    with(new Mortgage(property: $property, borrower: $borrower, params: $params), function (Mortgage $mortgage) use ($contract) {
        expect($mortgage->getPercentDownPayment())->toBe($contract->percent_down_payment);
        expect($mortgage->getPercentMiscellaneousFees())->toBe($contract->percent_miscellaneous_fees);
        expect((float) $mortgage->getDownPaymentTerm())->toBe($contract->down_payment_term);//TODO: update $mortgage->getDownPaymentTerm(), return float
        expect((float) $mortgage->getBalancePaymentTerm())->toBe($contract->balance_payment_term);//TODO: update $mortgage->getBalancePaymentTerm(), return float
        expect($mortgage->getInterestRate())->toBe($contract->interest_rate);
        expect(MortgageData::from($contract->mortgage)->toArray())->toBe(MortgageData::from($mortgage)->toArray());
    });
})->with('customer', 'inventory', 'params');

it('can compute mortgage from relations only', function(Contact $customer, Inventory $inventory, array $params) {
    $contract = new Contract;
    $contract->customer = $customer;
    $contract->inventory = $inventory;
    $contract->save();
    $contract->load('customer', 'inventory');

    $borrower = (new Borrower)->setGrossMonthlyIncome($customer->getGrossMonthlyIncome())->setBirthdate($customer->getBirthdate());
    $property = (new Property)->setTotalContractPrice($inventory->product->getTotalContractPrice())->setAppraisedValue($inventory->product->getAppraisedValue());

    with(new Mortgage(property: $property, borrower: $borrower, params: []), function (Mortgage $mortgage) use ($borrower, $property, $contract) {
        expect($property->getPercentDownPayment())->toBe(config('property.default.percent_dp'));
        expect(config('property.default.percent_dp'))->toBe($percent_dp = 0.1);
        expect($mortgage->getPercentDownPayment())->toBe($percent_dp);

        expect($property->getDownPaymentTerm())->toBe(config('property.default.dp_term'));
        expect(config('property.default.dp_term'))->toBe($dp_term = 6);
        expect((float) $mortgage->getDownPaymentTerm())->toBe((float) $dp_term);

        expect($property->getPercentMiscellaneousFees())->toBe(config('property.default.percent_mf'));
        expect(config('property.default.percent_mf'))->toBe($percent_mf = 0.05);
        expect($mortgage->getPercentMiscellaneousFees())->toBe($percent_mf);

        expect((float) $mortgage->getBalancePaymentTerm())->toBe((float) $borrower->getMaximumTermAllowed());
        expect($mortgage->getInterestRate())->toBe($property->getDefaultAnnualInterestRateFromBorrower($borrower));

        expect(MortgageData::from($contract->mortgage)->toArray())->toBe(MortgageData::from($mortgage)->toArray());
    });
})->with('customer', 'inventory', 'params');

it('has dated status', function() {
    $contract = new Contract;
    //consulted
    expect($contract->consulted)->toBeFalse();
    expect($contract->consulted_at)->toBeNull();
    $contract->consulted = (bool) ($dt = now());
    expect($contract->consulted)->toBeTrue();
    expect($contract->consulted_at)->toBeInstanceOf(Carbon::class);
    expect($contract->consulted_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
    //availed
    expect($contract->availed)->toBeFalse();
    expect($contract->availed_at)->toBeNull();
    $contract->availed = (bool) ($dt = now());
    expect($contract->availed)->toBeTrue();
    expect($contract->availed_at)->toBeInstanceOf(Carbon::class);
    expect($contract->availed_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
    //verified
    expect($contract->verified)->toBeFalse();
    expect($contract->verified_at)->toBeNull();
    $contract->verified = (bool) ($dt = now());
    expect($contract->verified)->toBeTrue();
    expect($contract->verified_at)->toBeInstanceOf(Carbon::class);
    expect($contract->verified_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
    //onboarded
    expect($contract->onboarded)->toBeFalse();
    expect($contract->onboarded_at)->toBeNull();
    $contract->onboarded = (bool) ($dt = now());
    expect($contract->onboarded)->toBeTrue();
    expect($contract->onboarded_at)->toBeInstanceOf(Carbon::class);
    expect($contract->onboarded_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
    //paid
    expect($contract->paid)->toBeFalse();
    expect($contract->paid_at)->toBeNull();
    $contract->paid = (bool) ($dt = now());
    expect($contract->paid)->toBeTrue();
    expect($contract->paid_at)->toBeInstanceOf(Carbon::class);
    expect($contract->paid_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
    //approved
    expect($contract->approved)->toBeFalse();
    expect($contract->approved_at)->toBeNull();
    $contract->approved = (bool) ($dt = now());
    expect($contract->approved)->toBeTrue();
    expect($contract->approved_at)->toBeInstanceOf(Carbon::class);
    expect($contract->approved_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
    //qualified
    expect($contract->qualified)->toBeFalse();
    expect($contract->qualified_at)->toBeNull();
    $contract->qualified = (bool) ($dt = now());
    expect($contract->qualified)->toBeTrue();
    expect($contract->qualified_at)->toBeInstanceOf(Carbon::class);
    expect($contract->qualified_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
    //disapproved
    expect($contract->disapproved)->toBeFalse();
    expect($contract->disapproved_at)->toBeNull();
    $contract->disapproved = (bool) ($dt = now());
    expect($contract->disapproved)->toBeTrue();
    expect($contract->disapproved_at)->toBeInstanceOf(Carbon::class);
    expect($contract->disapproved_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
    //overridden
    expect($contract->overridden)->toBeFalse();
    expect($contract->overridden_at)->toBeNull();
    $contract->overridden = (bool) ($dt = now());
    expect($contract->overridden)->toBeTrue();
    expect($contract->overridden_at)->toBeInstanceOf(Carbon::class);
    expect($contract->overridden_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
    //cancelled
    expect($contract->cancelled)->toBeFalse();
    expect($contract->cancelled_at)->toBeNull();
    $contract->cancelled = (bool) ($dt = now());
    expect($contract->cancelled)->toBeTrue();
    expect($contract->cancelled_at)->toBeInstanceOf(Carbon::class);
    expect($contract->cancelled_at->diffInMilliseconds($dt, true))->toBeLessThan(1);
});

dataset('reference', function () {
    return [
        [fn() => app(CreateReferenceAction::class)->run([
            InputFieldName::SKU => fake()->word(),
            InputFieldName::WAGES => fake()->numberBetween(10000, 120000) * 1.00,
            InputFieldName::TCP => fake()->numberBetween(850000, 4000000) * 1.00,
            InputFieldName::PERCENT_DP => fake()->numberBetween(5, 10)/100,
            InputFieldName::PERCENT_MF => fake()->numberBetween(8, 10)/100,
            InputFieldName::DP_TERM => fake()->numberBetween(12, 24) * 1.00,
            InputFieldName::BP_TERM => fake()->numberBetween(20, 30) * 1.00,
            InputFieldName::BP_INTEREST_RATE => fake()->numberBetween(3, 7)/100,
            InputFieldName::SELLER_COMMISSION_CODE => fake()->word(),
            InputFieldName::PROMO_CODE => fake()->word(),
        ], ['author' => 'Lester'])]
    ];
});

dataset('qr_code_url', function () {
    return [
        [fn() => 'https://pay.wepayez.com/qrcode/code?uuid=18c4185824a70bc58e8f569c0c671b251']
    ];
});

it('has states', function(Reference $reference, Contact $customer, string $qr_code_url) {
    $contract = new Contract;
    $contract->customer = $customer;
    $contract->save();
    $contract->load('customer');

    Event::fake(StateChanged::class);

    /** happy path */
    expect($contract->state)->toBeInstanceOf(Pending::class);

    expect($contract->consulted)->toBeFalse();
    $contract->state->transitionTo(Consulted::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof PendingToConsulted && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Consulted::class);
    expect($contract->consulted)->toBeTrue();

    expect($contract->availed)->toBeFalse();
    $contract->state->transitionTo(Availed::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof ConsultedToAvailed && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Availed::class);
    expect($contract->availed)->toBeTrue();

    expect($contract->verified)->toBeFalse();
    $contract->state->transitionTo(Verified::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof AvailedToVerified && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Verified::class);
    expect($contract->verified)->toBeTrue();

    expect($contract->onboarded)->toBeFalse();
    $contract->state->transitionTo(Onboarded::class, qr_code_url: $qr_code_url, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference, $qr_code_url) {
        return
            $state->transition instanceof VerifiedToOnboarded &&
            $state->transition->getReferenceCode() == $reference->code &&
            $state->transition->getQRCodeUrl() == $qr_code_url;
    });
    expect($contract->state)->toBeInstanceOf(Onboarded::class);
    expect($contract->onboarded)->toBeTrue();
    Notification::assertSentTo($contract->customer, function(VerifiedToOnboardedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->paid)->toBeFalse();
    $contract->state->transitionTo(Paid::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof OnboardedToPaid && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Paid::class);
    expect($contract->paid)->toBeTrue();
    Notification::assertSentTo($contract, function(OnboardedToPaidBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->assigned)->toBeFalse();
    $contract->state->transitionTo(Assigned::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof PaidToAssigned && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Assigned::class);
    expect($contract->assigned)->toBeTrue();
    Notification::assertSentTo($contract, function(PaidToAssignedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->acknowledged)->toBeFalse();
    $contract->state->transitionTo(Acknowledged::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof AssignedToAcknowledged && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Acknowledged::class);
    expect($contract->acknowledged)->toBeTrue();
    Notification::assertSentTo($contract, function(AssignedToAcknowledgedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->prequalified)->toBeFalse();
    $contract->state->transitionTo(Prequalified::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof AcknowledgedToPrequalified && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Prequalified::class);
    expect($contract->prequalified)->toBeTrue();
    Notification::assertSentTo($contract, function(AcknowledgedToPrequalifiedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->qualified)->toBeFalse();
    $contract->state->transitionTo(Qualified::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof PrequalifiedToQualified && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Qualified::class);
    expect($contract->qualified)->toBeTrue();
    Notification::assertSentTo($contract, function(PrequalifiedToQualifiedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->approved)->toBeFalse();
    $contract->state->transitionTo(Approved::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof QualifiedToApproved && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Approved::class);
    expect($contract->approved)->toBeTrue();
    Notification::assertSentTo($contract, function(QualifiedToApprovedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->validated)->toBeFalse();
    $contract->state->transitionTo(Validated::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof ApprovedToValidated && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Validated::class);
    expect($contract->validated)->toBeTrue();
    Notification::assertSentTo($contract, function(ApprovedToValidatedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->cancelled)->toBeFalse();
    $contract->state->transitionTo(Cancelled::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof ValidatedToCancelled && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Cancelled::class);
    expect($contract->cancelled)->toBeTrue();
    Notification::assertSentTo($contract, function(ValidatedToCancelledBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    /** payment failed then paid */
    $contract = new Contract;
    $contract->customer = $customer;
    $contract->save();
    $contract->state->transitionTo(Consulted::class);
    $contract->state->transitionTo(Availed::class);
    $contract->state->transitionTo(Verified::class);
    $contract->state->transitionTo(Onboarded::class, qr_code_url: $qr_code_url);
    expect($contract->payment_failed)->toBeFalse();
    $contract->state->transitionTo(PaymentFailed::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof OnboardedToPaymentFailed && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(PaymentFailed::class);
    expect($contract->payment_failed)->toBeTrue();
    Notification::assertSentTo($contract, function(OnboardedToPaymentFailedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->paid)->toBeFalse();
    $contract->state->transitionTo(Paid::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof PaymentFailedToPaid && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Paid::class);
    expect($contract->paid)->toBeTrue();
    Notification::assertSentTo($contract, function(PaymentFailedToPaidBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    /** idled then acknowledged */
    $contract = new Contract;
    $contract->customer = $customer;
    $contract->save();
    $contract->state->transitionTo(Consulted::class);
    $contract->state->transitionTo(Availed::class);
    $contract->state->transitionTo(Verified::class);
    $contract->state->transitionTo(Onboarded::class, qr_code_url: $qr_code_url);
    $contract->state->transitionTo(Paid::class);
    $contract->state->transitionTo(Assigned::class);

    expect($contract->idled)->toBeFalse();
    $contract->state->transitionTo(Idled::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof AssignedToIdled && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Idled::class);
    expect($contract->idled)->toBeTrue();
    Notification::assertSentTo($contract, function(AssignedToIdledBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->acknowledged)->toBeFalse();
    $contract->state->transitionTo(Acknowledged::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof IdledToAcknowledged && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Acknowledged::class);
    expect($contract->acknowledged)->toBeTrue();
    Notification::assertSentTo($contract, function(IdledToAcknowledgedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    /** disapproved but overridden then validated */
    $contract = new Contract;
    $contract->customer = $customer;
    $contract->save();
    $contract->state->transitionTo(Consulted::class);
    $contract->state->transitionTo(Availed::class);
    $contract->state->transitionTo(Verified::class);
    $contract->state->transitionTo(Onboarded::class, qr_code_url: $qr_code_url);
    $contract->state->transitionTo(Paid::class);
    $contract->state->transitionTo(Assigned::class);
    $contract->state->transitionTo(Acknowledged::class);
    $contract->state->transitionTo(Prequalified::class);
    $contract->state->transitionTo(Qualified::class);

    expect($contract->disapproved)->toBeFalse();
    $contract->state->transitionTo(Disapproved::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof QualifiedToDisapproved && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Disapproved::class);
    expect($contract->disapproved)->toBeTrue();
    Notification::assertSentTo($contract, function(QualifiedToDisapprovedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->overridden)->toBeFalse();
    $contract->state->transitionTo(Overridden::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof DisapprovedToOverridden && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Overridden::class);
    expect($contract->overridden)->toBeTrue();
    Notification::assertSentTo($contract, function(DisapprovedToOverriddenBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    expect($contract->validated)->toBeFalse();
    $contract->state->transitionTo(Validated::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof OverriddenToValidated && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(Validated::class);
    expect($contract->validated)->toBeTrue();
    Notification::assertSentTo($contract, function(OverriddenToValidatedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

    /** failed */
    $contract = new Contract;
    $contract->customer = $customer;
    $contract->save();
    $contract->state->transitionTo(Consulted::class);
    $contract->state->transitionTo(Availed::class);
    $contract->state->transitionTo(Verified::class);
    $contract->state->transitionTo(Onboarded::class, qr_code_url: $qr_code_url);
    $contract->state->transitionTo(Paid::class);
    $contract->state->transitionTo(Assigned::class);
    $contract->state->transitionTo(Acknowledged::class);
    $contract->state->transitionTo(Prequalified::class);

    expect($contract->not_qualified)->toBeFalse();
    $contract->state->transitionTo(NotQualified::class, reference: $reference);
    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
        return $state->transition instanceof PrequalifiedToNotQualified && $state->transition->getReferenceCode() == $reference->code;
    });
    expect($contract->state)->toBeInstanceOf(NotQualified::class);
    expect($contract->not_qualified)->toBeTrue();
    Notification::assertSentTo($contract, function(PrequalifiedToNotQualifiedBuyerNotification $notification) use ($reference) {
        return $notification->getReferenceData()->code == $reference->code;
    });

})->with('reference', 'customer', 'qr_code_url');

it('has data', function(Contact $customer, Inventory $inventory, array $params) {
    $contract = new Contract;
    $contract->customer = $customer;
    $contract->inventory = $inventory;
    $contract->percent_down_payment = $params[Input::PERCENT_DP];
    $contract->percent_miscellaneous_fees = $params[Input::PERCENT_MF];
    $contract->down_payment_term = $params[Input::DP_TERM];
    $contract->balance_payment_term = $params[Input::BP_TERM];
    $contract->interest_rate = $params[Input::BP_INTEREST_RATE];
    $contract->save();
    $contract->state->transitionTo(Consulted::class);
    $contract->load('customer', 'inventory');
    $borrower = (new Borrower)->setGrossMonthlyIncome($customer->getGrossMonthlyIncome())->setBirthdate($customer->getBirthdate());
    $property = (new Property)->setTotalContractPrice($inventory->product->getTotalContractPrice())->setAppraisedValue($inventory->product->getAppraisedValue());

    with(new Mortgage(property: $property, borrower: $borrower, params: $params), function (Mortgage $mortgage) use ($contract) {
        $data = ContractData::fromModel($contract);
        expect($data->reference_code)->toBeNull();
        expect($data->customer)->toBeInstanceOf(ContactData::class);
        expect($data->inventory)->toBeInstanceOf(PropertyData::class);
        expect($data->mortgage)->toBeInstanceOf(MortgageData::class);
        expect($data->state)->toBe('consulted');
        expect($data->consulted_at)->toBeInstanceOf(Carbon::class);
        expect($data->availed_at)->toBeNull();
        expect($data->verified_at)->toBeNull();
        expect($data->onboarded_at)->toBeNull();
        expect($data->paid_at)->toBeNull();
        expect($data->approved_at)->toBeNull();
        expect($data->disapproved_at)->toBeNull();
        expect($data->overridden_at)->toBeNull();
        expect($data->cancelled_at)->toBeNull();
        expect($data->consulted)->toBeTrue();
        expect($data->availed)->toBeFalse();
        expect($data->verified)->toBeFalse();
        expect($data->onboarded)->toBeFalse();
        expect($data->paid)->toBeFalse();
        expect($data->approved)->toBeFalse();
        expect($data->disapproved)->toBeFalse();
        expect($data->overridden)->toBeFalse();
        expect($data->cancelled)->toBeFalse();
    });
})->with('customer', 'inventory', 'params')->skip();

test('data from factory works', function () {
    $contract = Contract::factory()->create();
    expect(ContractData::fromModel($contract))->toBeInstanceOf(ContractData::class);
});


dataset('contact attributes', function() {
    return [
        [fn() => Contact::factory()
            ->state(       ['date_of_birth' => '1999-03-17'])
            ->withId('AACS-08022025-537')->make()->toArray()]
    ];
});

dataset('property attributes', function() {
    return [
        [fn() => [
            "code" => "PVMP-01-002-001",
            "name" => "Pagsibol Village Magalang Pampanga Duplex",
            "type" => "House and Lot",
            "cluster" => "1",
            "phase" => "1",
            "block" => "2",
            "lot" => "1",
            "building" => "",
            "floor_area" => 45.5,
            "lot_area" => 54,
            "unit_type" => "Two Storey Duplex",
            "unit_type_interior" => "Bare",
            "house_color" => "",
            "roof_style" => "",
            "end_unit" => false,
            "veranda" => false,
            "balcony" => false,
            "firewall" => false,
            "eaves" => false,
            "bedrooms" => 0,
            "toilets_and_bathrooms" => 0,
            "parking_slots" => 0,
            "carports" => 0,
            "project_code" => "PVMP",
            "project_location" => "Magalang, Pampanga",
            "project_address" => "Brgy. San Isidro, Magalang, Pampanga",
            "sku" => "JN-PVMP-HLDU-54-h",
            "tcp" => 1580000,
            "product" => [
                "sku" => "JN-PVMP-HLDU-54-h",
                "name" => "Pagsibol Village Magalang Pampanga Duplex",
                "brand" => "Pagsibol Village Magalang Pampanga",
                "category" => "",
                "description" => "Pagsibol is a ....",
                "price" => 1580000,
                "market_segment" => "",
                "location" => "",
                "destinations" => "",
                "directions" => "",
                "amenities" => "",
                "facade_url" => "",
                "project_location" => "",
                "project_code" => "",
                "property_name" => "",
                "phase" => "",
                "block" => "",
                "lot" => "",
                "lot_area" => 0,
                "floor_area" => 0,
                "project_address" => "",
                "property_type" => "",
                "unit_type" => "",
                "appraised_value" => 1580000,
                "percent_down_payment" => 0.1,
                "down_payment_term" => 12,
                "percent_miscellaneous_fees" => 0.085,
            ],
        "project" => [
            'code' => 'PVMP',
            'name' => 'Pagsibol Village',
            'location' => 'Pampanga',
            'type' => 'Project Type'
        ],
        ]]
    ];
});

test('contract contact works', function (array $array) {
    $metadata = \Homeful\Contacts\Classes\ContactMetaData::from($array);
    $contract = Contract::factory()->create();
    $contract->update(['contact' => $metadata]);
    $contract->save();
    expect($contract->getAttribute('contact'))->toBeInstanceOf(\Homeful\Contacts\Classes\ContactMetaData::class);
})->with('contact attributes');

test('contract property works', function (array $array) {
    $metadata = PropertyData::from($array);
    $contract = Contract::factory()->create();
    $contract->update(['property' => $metadata]);
    $contract->save();
    expect($contract->getAttribute('property'))->toBeInstanceOf(PropertyData::class);
})->with('property attributes');

test('contract mortgage from contact and property', function (array $contact_attributes, array $property_attributes) {
    $contract = Contract::factory()->create();
    $contract->update(['contact' => $contact_attributes]);
    $contract->update(['property' => $property_attributes]);
    $contract->save();
    expect($contract->getAttribute('mortgage'))->toBeInstanceOf(Mortgage::class);
    //TODO: assert $contract_attributes are in mortgage->getBorrower() attributes
    //TODO: assert $property_attributes are in mortgage->getProperty() attributes e.g., sku
})->with('contact attributes', 'property attributes');

test('contract data works without mortgage', function (){
    $contract = Contract::create();
    $entities = [
        'contract' => $contract
    ];
    $reference = References::withEntities(...$entities)->withStartTime(now())->create();
    $contract->state->transitionTo(Consulted::class, $reference);
    expect($contract->consulted_at)->toBeInstanceOf(Carbon::class);
    expect($contract->consulted)->toBeTrue();
});

dataset('checkin_payload', function () {
    return [
        [fn() => Lead::factory()->getCheckinPayload([
            'email' => fake()->email(),
            'mobile' => '09171234567',
            'code' => fake()->word(),
            'identifier' => fake()->word(),
            'choice' => fake()->word(),
            'location' => fake()->latitude() .',' . fake()->longitude(),
            'fullName' => fake()->name(),
            'address' => fake()->city(),
            'dateOfBirth' => '1999-03-17',
            'idType' => 'phl_dl',
            'idNumber' => 'ID-123456'
        ])]
    ];
});

test('contact has checkin', function (array $checkin_payload) {
    $contract = Contract::create();
    expect($contract->checkin)->toBeNull();
    $contract->update(['checkin' => $checkin_payload]);
    $contract->save();
    expect($contract->checkin)->toBeInstanceOf(CheckinData::class);
})->with('checkin_payload');

test('contact checkin can accept json (string)', function (array $checkin_payload) {
    $contract = Contract::create();
    expect($contract->checkin)->toBeNull();
    $json = json_encode($checkin_payload);
    $contract->update(['checkin' => $json]);
    $contract->save();
    expect($contract->checkin)->toBeInstanceOf(CheckinData::class);
})->with('checkin_payload');

dataset('payment_payload', function () {
    return [
        [fn() => json_decode('{"code":"00","data":{"orderInformation":{"amount":5000,"attach":"attach","currency":"PHP","goodsDetail":"Processing Fee","orderAmount":0,"orderId":"JN123456722","paymentBrand":"MasterCard","paymentType":"PAYMENT","qrTag":1,"referencedId":"202410302883035985507708928","responseDate":"2024-10-30T15:23:29+08:00","surcharge":0,"tipFee":0,"transactionResult":"SUCCESS"}},"message":"Success"}',true)]
    ];
});

test('contact payment can accept array', function (array $payment_payload) {
    $contract = Contract::create();
    expect($contract->payment)->toBeNull();
    $contract->update(['payment' => $payment_payload]);
    $contract->save();
    expect($contract->payment)->toBeInstanceOf(PaymentData::class);
})->with('payment_payload');

test('contract has loan term attribute', function () {
    $contract = Contract::factory()->create();
    $loan_term_option = [
        'term_1' => '1 - 36',
        'term_2' => '37 - 348',
        'term_3' => '349 - 360',
        'months_term' => 360,
        'loanable_years' => 30,
    ];
    $contract->loan_term_option = $loan_term_option;
    $contract->save();
    expect($contract->loan_term_option->toArray())->toBe($loan_term_option);
    expect(LoanTermOptionData::from($contract->loan_term_option))->toBeInstanceOf(LoanTermOptionData::class);
});
