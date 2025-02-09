<?php

namespace Homeful\Contracts\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Homeful\Properties\Models\Property as Inventory;
use Homeful\Contacts\Models\Customer as Contact;

trait HasInputRelations
{
    /**
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            related: config(key: 'contracts.models.customer', default: Contact::class),
            foreignKey: 'contact_id',
            ownerKey: 'id',
            relation: 'contacts'
        );
    }

    /**
     *
     * @param Contact $customer
     * @return HasInputRelations|\Homeful\Contracts\Models\Contract
     */
    public function setCustomerAttribute(Contact $customer): self
    {
        //TODO: create validation of customer
        $this->customer()->associate($customer);
        $this->load('customer');

        return $this;
    }

    /**
     *
     * @return BelongsTo
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(
            related: config(key: 'contracts.models.inventory', default: Inventory::class),
            foreignKey: 'property_code',
            ownerKey: 'code',
            relation: 'properties'
        );
    }

    /**
     *
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
