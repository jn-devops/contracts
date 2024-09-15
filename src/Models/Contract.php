<?php

namespace Homeful\Contracts\Models;

use Homeful\Common\Traits\HasPackageFactory as HasFactory;
use Homeful\Contracts\Traits\HasDatedStatusAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Homeful\Properties\Models\Property as Inventory;
use Homeful\Contacts\Models\Contact as Customer;
use Homeful\Contracts\Traits\HasInputAttributes;
use Homeful\Contracts\Traits\HasInputRelations;
use Homeful\Contracts\States\ContractState;
use Illuminate\Database\Eloquent\Model;
use Homeful\Common\Traits\HasMeta;
use Spatie\ModelStates\HasStates;
use Homeful\Mortgage\Mortgage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class Contract
 *
 * @property string $id
 * @property Customer $customer
 * @property Inventory $inventory
 * @property float $percent_down_payment
 * @property float $percent_miscellaneous_fees
 * @property float $down_payment_term
 * @property float $balance_payment_term
 * @property float $interest_rate
 * @property Mortgage $mortgage
 * @property ContractState $state
 * @property Carbon $consulted_at
 * @property Carbon $availed_at
 * @property Carbon $verified_at
 * @property Carbon $onboarded_at
 * @property Carbon $paid_at
 * @property Carbon $payment_failed_at
 * @property Carbon $assigned_at
 * @property Carbon $idled_at
 * @property Carbon $acknowledged_at
 * @property Carbon $prequalified_at
 * @property Carbon $qualified_at
 * @property Carbon $not_qualified_at
 * @property Carbon $approved_at
 * @property Carbon $disapproved_at
 * @property Carbon $validated_at
 * @property Carbon $overridden_at
 * @property Carbon $cancelled_at
 * @property bool $consulted
 * @property bool $availed
 * @property bool $verified
 * @property bool $onboarded
 * @property bool $paid
 * @property bool $payment_failed
 * @property bool $assigned
 * @property bool $idled
 * @property bool $acknowledged
 * @property bool $prequalified
 * @property bool $qualified
 * @property bool $not_qualified
 * @property bool $approved
 * @property bool $disapproved
 * @property bool $validated
 * @property bool $overridden
 * @property bool $cancelled
 * @property SchemalessAttributes $meta
 * @property string $seller_commission_code
 *
 * @method Model create()
 * @method int getKey()
 */
class Contract extends Model
{
    use HasInputAttributes, HasInputRelations, HasDatedStatusAttributes;
    use HasFactory;
    use HasMeta;
    use HasStates;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'customer',
        'inventory',
        'reference_code',
        'seller_commission_code',
    ];

    protected $casts = [
        'state' => ContractState::class
    ];

    public static function booted(): void
    {
        static::creating(function (Contract $contract) {
            $contract->id = Str::uuid()->toString();
        });
    }

    /**
     * @param Mortgage $value
     * @return $this
     */
    public function setMortgageAttribute(Mortgage $value): self
    {
        $this->getAttribute('meta')->set('mortgage', serialize($value));

        return $this;
    }

    /**
     * @return Mortgage|null
     */
    public function getMortgageAttribute(): ?Mortgage
    {
        $serialized = $this->getAttribute('meta')->get('mortgage');

        return $serialized ? unserialize($serialized) : null;
    }
}
