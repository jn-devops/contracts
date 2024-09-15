<?php

namespace Homeful\Contracts\Database\Factories;

use Homeful\Properties\Models\Property as Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\Contacts\Models\Contact as Customer;
use Homeful\Contracts\Models\Contract;
use Homeful\Products\Models\Product;

class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
        $product = Product::factory()->create(['price' => 2500000]);
        $inventory = Inventory::factory()->create(['sku' => $product->sku]);

        return [
            'contact_id' => Customer::factory()->state(['date_of_birth' => '1999-03-17', 'employment' => [0 => ['type' => 'buyer', 'monthly_gross_income' => 50000]]])->create(),
            'property_code' => $inventory->getAttribute('code'),
            'percent_down_payment' => $this->faker->numberBetween(5, 10)/100,
            'percent_miscellaneous_fees' => $this->faker->numberBetween(8, 10)/100,
            'down_payment_term' => $this->faker->numberBetween(12, 24) * 1.00,
            'balance_payment_term' => $this->faker->numberBetween(20, 30) * 1.00,
            'interest_rate' => $this->faker->numberBetween(3, 7)/100,
            'seller_commission_code' => $this->faker->word(),
        ];
    }
}
