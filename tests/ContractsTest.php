<?php

use Homeful\Notifications\Notifications\PostPaymentBuyerNotification;
use Homeful\Contracts\Transitions\VerifiedToOnboarded;
use Homeful\References\Actions\CreateReferenceAction;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Homeful\Properties\Models\Property as Inventory;

use Homeful\Common\Classes\Input as InputFieldName;
use Homeful\Contacts\Models\Contact as Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\ModelStates\Events\StateChanged;
use Homeful\Contracts\States\PaymentFailed;
use Homeful\Contracts\States\Prequalified;
use Homeful\Contracts\States\NotQualified;
use Homeful\Properties\Data\PropertyData;
use Homeful\Contracts\States\Disapproved;
use Homeful\Contracts\States\Overridden;
use Homeful\Contracts\Data\ContractData;
use Homeful\References\Models\Reference;
use Homeful\Contracts\States\Qualified;
use Homeful\Contracts\States\Consulted;
use Homeful\Contracts\States\Cancelled;
use Homeful\Contracts\States\Onboarded;
use Homeful\Mortgage\Data\MortgageData;
use Homeful\Contacts\Data\ContactData;
use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Approved;
use Homeful\Contracts\States\Verified;
use Homeful\Contracts\States\Pending;
use Homeful\Contracts\States\Availed;
use Illuminate\Support\Facades\Event;
use Homeful\Products\Models\Product;
use Homeful\Contracts\States\Paid;
use Homeful\Common\Classes\Input;
use Homeful\Borrower\Borrower;
use Homeful\Property\Property;
use Homeful\Mortgage\Mortgage;
use Illuminate\Support\Carbon;
use Homeful\Contracts\States\Assigned;
use Homeful\Contracts\States\Idled;
use Homeful\Contracts\States\Acknowledged;
use Homeful\Contracts\States\Validated;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    Notification::fake();
    $migration = include 'vendor/jn-devops/contacts/database/migrations/create_contacts_table.php.stub';
    $migration->up();
    $migration = include 'vendor/jn-devops/products/database/migrations/create_products_table.php.stub';
    $migration->up();
    $migration = include 'vendor/jn-devops/properties/database/migrations/create_properties_table.php.stub';
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
        fn () => Customer::factory(['date_of_birth' => '1999-03-17', 'employment' => [0 => ['type' => 'buyer', 'monthly_gross_income' => 50000]]])->create()
    ];
});

dataset('inventory', function() {
    return [
        fn () => Inventory::factory()->for(Product::factory()->state(['price' => 2500000]))->create()
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

it('has simple attributes', function () {
    with(Contract::factory()->create(), function ($contract) {
        expect($contract->customer)->toBeInstanceOf(Customer::class);
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
        expect($contract->reference_code)->toBeString();
        expect($contract->seller_commission_code)->toBeString();
    });
});

it('has optional attributes', function(Customer $customer, Inventory $inventory, array $params) {
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
        expect($contract->reference_code)->toBeNull();
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
        expect($contract->customer)->toBeInstanceOf(Customer::class);
        expect($contract->inventory)->toBeInstanceOf(Inventory::class);
//        UpdateMortgage::run($contract);
        expect($contract->mortgage)->toBeInstanceOf(Mortgage::class);
    });

})->with('customer', 'inventory', 'params');

it('can be filled', function(Customer $customer, Inventory $inventory, array $params) {
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
    expect($contract->customer)->toBeInstanceOf(Customer::class);
    expect($contract->inventory)->toBeInstanceOf(Inventory::class);
    expect($contract->mortgage)->toBeInstanceOf(Mortgage::class);
})->with('customer', 'inventory', 'params');

it('has a customer relation', function(Customer $customer) {
    with(Contract::factory()->for(factory: $customer, relationship: 'customer')->create(), function (Contract $contract) use ($customer) {
        expect($contract->customer->is($customer))->toBeTrue();
        expect($contract->customer->getBirthDate()->eq('1999-03-17'))->toBeTrue();
        expect($contract->customer->getGrossMonthlyIncome()->inclusive()->compareTo(50000))->toBe(0);
    });
})->with( 'customer');

it('can set customer', function(Customer $customer) {
    $contract = new Contract;
    $contract->save();
    expect($contract->customer)->toBeNull();
    $contract->customer = $customer;
    $contract->save();
    expect($contract->customer->is($customer))->toBeTrue();
})->with('customer');

it('has an inventory relation', function (Inventory $inventory) {
    with(Contract::factory()->for(factory: $inventory, relationship: 'inventory')->create(), function (Contract $contract) use ($inventory) {
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

it('can compute mortgage from input attributes', function(Customer $customer, Inventory $inventory, array $params) {
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
            InputFieldName::PERCENT_DP => $this->faker->numberBetween(5, 10)/100,
            InputFieldName::PERCENT_MF => $this->faker->numberBetween(8, 10)/100,
            InputFieldName::DP_TERM => $this->faker->numberBetween(12, 24) * 1.00,
            InputFieldName::BP_TERM => $this->faker->numberBetween(20, 30) * 1.00,
            InputFieldName::BP_INTEREST_RATE => $this->faker->numberBetween(3, 7)/100,
            InputFieldName::SELLER_COMMISSION_CODE => $this->faker->word(),
        ], ['author' => 'Lester'])]
    ];
});

it('has states', function(Reference $reference, Customer $customer) {
    $contract = new Contract;
    $contract->customer = $customer;
    $contract->save();
    $contract->load('customer');

    expect($contract->state)->toBeInstanceOf(Pending::class);

    expect($contract->consulted)->toBeFalse();
    $contract->state->transitionTo(Consulted::class);
    expect($contract->state)->toBeInstanceOf(Consulted::class);
    expect($contract->consulted)->toBeTrue();

    expect($contract->availed)->toBeFalse();
    $contract->state->transitionTo(Availed::class);
    expect($contract->state)->toBeInstanceOf(Availed::class);
    expect($contract->availed)->toBeTrue();

    expect($contract->verified)->toBeFalse();
    $contract->state->transitionTo(Verified::class);
    expect($contract->state)->toBeInstanceOf(Verified::class);
    expect($contract->verified)->toBeTrue();

//    Event::fake(StateChanged::class);
    expect($contract->onboarded)->toBeFalse();
    $contract->state->transitionTo(Onboarded::class, reference: $reference);
    expect($contract->state)->toBeInstanceOf(Onboarded::class);
    expect($contract->onboarded)->toBeTrue();
//    Event::assertDispatched(StateChanged::class, function (StateChanged $state) use ($reference) {
//        return $state->transition instanceof VerifiedToOnboarded && $state->transition->getReferenceCode() == $reference->code;
//    });

    expect($contract->paid)->toBeFalse();
    $contract->state->transitionTo(Paid::class, reference: $reference);
    expect($contract->state)->toBeInstanceOf(Paid::class);
    expect($contract->paid)->toBeTrue();

//    Notification::assertSentTo($contract->customer, function(PostPaymentBuyerNotification $notification) use ($reference) {
//        return $notification->getReferenceData()->code == $reference->code;
//    });

    expect($contract->assigned)->toBeFalse();
    $contract->state->transitionTo(Assigned::class);
    expect($contract->state)->toBeInstanceOf(Assigned::class);
    expect($contract->assigned)->toBeTrue();

    expect($contract->acknowledged)->toBeFalse();
    $contract->state->transitionTo(Acknowledged::class);
    expect($contract->state)->toBeInstanceOf(Acknowledged::class);
    expect($contract->acknowledged)->toBeTrue();

    expect($contract->prequalified)->toBeFalse();
    $contract->state->transitionTo(Prequalified::class);
    expect($contract->state)->toBeInstanceOf(Prequalified::class);
    expect($contract->prequalified)->toBeTrue();

    expect($contract->qualified)->toBeFalse();
    $contract->state->transitionTo(Qualified::class);
    expect($contract->state)->toBeInstanceOf(Qualified::class);
    expect($contract->qualified)->toBeTrue();

    expect($contract->approved)->toBeFalse();
    $contract->state->transitionTo(Approved::class);
    expect($contract->state)->toBeInstanceOf(Approved::class);
    expect($contract->approved)->toBeTrue();

    expect($contract->validated)->toBeFalse();
    $contract->state->transitionTo(Validated::class);
    expect($contract->state)->toBeInstanceOf(Validated::class);
    expect($contract->validated)->toBeTrue();

    expect($contract->cancelled)->toBeFalse();
    $contract->state->transitionTo(Cancelled::class);
    expect($contract->state)->toBeInstanceOf(Cancelled::class);
    expect($contract->cancelled)->toBeTrue();

    /** payment failed */
    $contract = new Contract;
    $contract->state->transitionTo(Consulted::class);
    $contract->state->transitionTo(Availed::class);
    $contract->state->transitionTo(Verified::class);
    $contract->state->transitionTo(Onboarded::class);
    $contract->state->transitionTo(PaymentFailed::class);
    $contract->state->transitionTo(Paid::class);

    /** acknowledged */
    $contract = new Contract;
    $contract->state->transitionTo(Consulted::class);
    $contract->state->transitionTo(Availed::class);
    $contract->state->transitionTo(Verified::class);
    $contract->state->transitionTo(Onboarded::class);
    $contract->state->transitionTo(Paid::class);
    $contract->state->transitionTo(Assigned::class);
    $contract->state->transitionTo(Acknowledged::class);

    /** idled */
    $contract = new Contract;
    $contract->state->transitionTo(Consulted::class);
    $contract->state->transitionTo(Availed::class);
    $contract->state->transitionTo(Verified::class);
    $contract->state->transitionTo(Onboarded::class);
    $contract->state->transitionTo(Paid::class);
    $contract->state->transitionTo(Assigned::class);
    $contract->state->transitionTo(Idled::class);
    $contract->state->transitionTo(Acknowledged::class);

    /** not qualified */
    $contract = new Contract;
    $contract->state->transitionTo(Consulted::class);
    $contract->state->transitionTo(Availed::class);
    $contract->state->transitionTo(Verified::class);
    $contract->state->transitionTo(Onboarded::class);
    $contract->state->transitionTo(Paid::class);
    $contract->state->transitionTo(Assigned::class);
    $contract->state->transitionTo(Acknowledged::class);
    $contract->state->transitionTo(Prequalified::class);

//    expect($contract->overridden)->toBeFalse();
//    $contract->state->transitionTo(Overridden::class);
//    expect($contract->state)->toBeInstanceOf(Overridden::class);
//    expect($contract->overridden)->toBeTrue();
//
//    expect($contract->cancelled)->toBeFalse();
//    $contract->state->transitionTo(Cancelled::class);
//    expect($contract->state)->toBeInstanceOf(Cancelled::class);
//    expect($contract->cancelled)->toBeTrue();

    /** failed */
    $contract = new Contract;
    $contract->state->transitionTo(Consulted::class);
    $contract->state->transitionTo(Availed::class);
    $contract->state->transitionTo(Verified::class);
    $contract->state->transitionTo(Onboarded::class);
    $contract->state->transitionTo(Paid::class);
    $contract->state->transitionTo(Assigned::class);
    $contract->state->transitionTo(Acknowledged::class);
    $contract->state->transitionTo(Prequalified::class);
    $contract->state->transitionTo(NotQualified::class);

    expect($contract->disapproved)->toBeFalse();
    $contract->state->transitionTo(Disapproved::class);
    expect($contract->state)->toBeInstanceOf(Disapproved::class);
    expect($contract->disapproved)->toBeTrue();

    expect($contract->overridden)->toBeFalse();
    $contract->state->transitionTo(Overridden::class);
    expect($contract->state)->toBeInstanceOf(Overridden::class);
    expect($contract->overridden)->toBeTrue();

    expect($contract->cancelled)->toBeFalse();
    $contract->state->transitionTo(Cancelled::class);
    expect($contract->state)->toBeInstanceOf(Cancelled::class);
    expect($contract->cancelled)->toBeTrue();
})->with('reference', 'customer');

it('has data', function(Customer $customer, Inventory $inventory, array $params) {
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
        $data = ContractData::fromModel($contract);
        expect($data->reference_code)->toBeNull();
        expect($data->customer)->toBeInstanceOf(ContactData::class);
        expect($data->inventory)->toBeInstanceOf(PropertyData::class);
        expect($data->mortgage)->toBeInstanceOf(MortgageData::class);
        expect($data->state)->toBe(Pending::class);
        expect($data->consulted_at)->toBeNull();
        expect($data->availed_at)->toBeNull();
        expect($data->verified_at)->toBeNull();
        expect($data->onboarded_at)->toBeNull();
        expect($data->paid_at)->toBeNull();
        expect($data->approved_at)->toBeNull();
        expect($data->disapproved_at)->toBeNull();
        expect($data->overridden_at)->toBeNull();
        expect($data->cancelled_at)->toBeNull();
        expect($data->consulted)->toBeFalse();
        expect($data->availed)->toBeFalse();
        expect($data->verified)->toBeFalse();
        expect($data->onboarded)->toBeFalse();
        expect($data->paid)->toBeFalse();
        expect($data->approved)->toBeFalse();
        expect($data->disapproved)->toBeFalse();
        expect($data->overridden)->toBeFalse();
        expect($data->cancelled)->toBeFalse();
    });
})->with('customer', 'inventory', 'params');

test('data from factory works', function () {
    $contract = Contract::factory()->create();
    expect(ContractData::fromModel($contract))->toBeInstanceOf(ContractData::class);
});


