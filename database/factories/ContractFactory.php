<?php

namespace Homeful\Contracts\Database\Factories;

use Homeful\Contacts\Facades\Contacts;
//use Homeful\Contracts\Models\Contact;
use Homeful\Properties\Models\Property as Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\Contacts\Models\Customer as Contact;
use Homeful\Contracts\Models\Contract;
use Homeful\Products\Models\Product;

class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
//        $product = Product::factory()->create(['price' => 2500000]);
//        $inventory = Inventory::factory()->create(['sku' => $product->sku]);
//        $contact = Contact::factory()
//            ->state(['date_of_birth' => '1999-03-17'])
//            ->withEmployment([
//                0 => [
//                    'type' => 'Primary',
//                    'monthly_gross_income' => 60000.0,
//                    'current_position' => 'Developer',
//                ],
//                1 => [
//                    'type' => 'Sideline',
//                    'monthly_gross_income' => 20000.0,
//                    'current_position' => 'Freelancer',
//                ]
//            ])
//            ->withCoBorrowers([
//                0 => [
//                    'date_of_birth' => '1998-08-12',
//                    'employment' => [
//                        0 => [
//                            'type' => 'Primary',
//                            'monthly_gross_income' => 50000.0,
//                            'current_position' => 'Engineer',
//                        ]
//                    ]
//                ],
//                1 => [
//                    'date_of_birth' => '1995-01-24',
//                    'employment' => [
//                        0 => [
//                            'type' => 'Sideline',
//                            'monthly_gross_income' => 40000.0,
//                            'current_position' => 'Developer',
//                        ]
//                    ]
//                ]
//            ])
//            ->create();

        return [
//            'contact_id' => $contact->id,
//            'property_code' => $inventory->getAttribute('code'),
            'percent_down_payment' => $this->faker->numberBetween(5, 10)/100,
            'percent_miscellaneous_fees' => $this->faker->numberBetween(8, 10)/100,
            'down_payment_term' => $this->faker->numberBetween(12, 24) * 1.00,
            'balance_payment_term' => $this->faker->numberBetween(20, 30) * 1.00,
            'interest_rate' => $this->faker->numberBetween(3, 7)/100,
            'seller_commission_code' => $this->faker->word(),
        ];
    }
}
