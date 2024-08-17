<?php

namespace Homeful\Contracts\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Homeful\Properties\Models\Property as Inventory;
use Homeful\Contacts\Models\Contact as Customer;

trait HasInputRelations
{
    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'contact_id', 'id', 'contacts');
    }

    /**
     * @param Customer $customer
     * @return HasInputRelations|\Homeful\Contracts\Models\Contract
     */
    public function setCustomerAttribute(Customer $customer): self
    {
        //TODO: create validation of customer
        $this->customer()->associate($customer);
        $this->load('customer');

        return $this;
    }

    /**
     * @return BelongsTo
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'property_code', 'code', 'properties');
    }

    /**
     * @param Inventory $inventory
     * @return HasInputRelations|\Homeful\Contracts\Models\Contract
     */
    public function setInventoryAttribute(Inventory $inventory): self
    {
        //TODO: create validation of inventory
        $this->inventory()->associate($inventory);
        $this->load('inventory');

        return $this;
    }
}
