<?php

use Homeful\Contacts\Classes\ContactMetaData;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Illuminate\Support\Facades\Notification;
use Homeful\Contacts\Models\Customer;

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

test('contact with minimum attributes has contact metadata', function () {
    $customer = Customer::create([
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => fake()->email(),
        'mobile' => '09181234567',
    ]);
    expect($customer)->toBeInstanceOf(Customer::class);
    expect(ContactMetaData::from($customer->toArray()))->toBeInstanceOf(ContactMetaData::class);
});

dataset('customer', function() {
    return [
        fn () => Customer::factory()
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
    ];
});

test('contact from factory has contact metadata', function (Customer $customer) {
    expect(ContactMetaData::from($customer->toArray()))->toBeInstanceOf(ContactMetaData::class);
    expect($data = $customer->getData())->toBeInstanceOf(ContactMetaData::class);
    if ($data instanceof ContactMetaData) {
        expect($data->date_of_birth->format('Y-m-d'))->toBe('1999-03-17');
        expect($data->employment->first()->monthly_gross_income)->toBe(60000.0);
    }
})->with('customer');
