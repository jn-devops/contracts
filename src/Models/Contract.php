<?php

namespace Homeful\Contracts\Models;

use Homeful\Common\Traits\HasPackageFactory as HasFactory;

use Homeful\Contracts\Traits\HasDatedStatusAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Homeful\Properties\Models\Property as Inventory;
use Spatie\LaravelData\{DataCollection, WithData};
use Homeful\Contracts\Traits\HasOptionsAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Homeful\Contacts\Models\Contact as Customer;
use Homeful\Contracts\Traits\HasInputAttributes;
use Homeful\Contracts\Traits\HasMiscAttributes;
use Homeful\Contracts\Traits\HasInputRelations;
use Homeful\Contracts\Data\LoanTermOptionData;
use Homeful\Contacts\Classes\ContactMetaData;
use Homeful\Contracts\States\ContractState;
use Illuminate\Notifications\Notification;
use Homeful\Properties\Data\PropertyData;
use Illuminate\Notifications\Notifiable;
use Homeful\Contracts\Data\ContractData;
use Illuminate\Database\Eloquent\Model;
use Homeful\KwYCCheck\Data\CheckinData;
use Homeful\Contracts\Data\PaymentData;
use Homeful\Common\Traits\HasMeta;
use Spatie\ModelStates\HasStates;
use Homeful\Mortgage\Mortgage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class Contract
 *
 * @property string $id
 * @property ContactMetaData|null $contact
 * @property PropertyData|null $property
 * @property CheckinData|null $checkin
 * @property PaymentData|null $payment
 * @property Customer|null $customer
 * @property Inventory|null $inventory
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
 * @property LoanTermOptionData $loan_term_option
 * @property SchemalessAttributes $misc
 * @property string $voucher_code
 * @property string $voucher_source
 * @property string $referral_code
 * @property string $source_of_sale
 * @property string $campaign_code
 * @property string $campaign_author
 * @property array $misc_inputs
 *
 * @method Model create()
 * @method int getKey()
 * @method ContractData getData()
 */
class Contract extends Model
{
    use HasInputAttributes, HasOptionsAttributes, HasInputRelations, HasDatedStatusAttributes, HasMiscAttributes;
    use HasFactory;
    use Notifiable;
    use HasStates;
    use WithData;
    use HasMeta;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'contact',
        'property',
        'checkin',
        'payment',
        'customer',
        'inventory',
//        'reference_code',
        'seller_commission_code',
    ];

    protected $casts = [
        'state' => ContractState::class,
        'contact' => ContactMetaData::class,
        'property' => PropertyData::class,
        'payment' => PaymentData::class,
        'consulted_at' => 'datetime:Y-m-d',
        'availed_at' => 'datetime:Y-m-d',
        'verified_at' => 'datetime:Y-m-d',
        'onboarded_at' => 'datetime:Y-m-d',
        'paid_at' => 'datetime:Y-m-d',
        'payment_failed_at' => 'datetime:Y-m-d',
        'assigned_at' => 'datetime:Y-m-d',
        'idled_at' => 'datetime:Y-m-d',
        'acknowledged_at' => 'datetime:Y-m-d',
        'prequalified_at' => 'datetime:Y-m-d',
        'qualified_at' => 'datetime:Y-m-d',
        'not_qualified_at' => 'datetime:Y-m-d',
        'approved_at' => 'datetime:Y-m-d',
        'validated_at' => 'datetime:Y-m-d',
        'disapproved_at' => 'datetime:Y-m-d',
        'overridden_at' => 'datetime:Y-m-d',
        'cancelled_at' => 'datetime:Y-m-d',
        'loan_term_option' => LoanTermOptionData::class,
    ];

    protected string $dataClass = ContractData::class;

    public static function booted(): void
    {
        static::creating(function (Contract $contract) {
            $contract->id = $contract->id ?: Str::uuid()->toString();
        });
    }

    public function routeNotificationForMail(Notification $notification)
    {
        return optional($this->contact)->email;
    }

    public function routeNotificationForEngageSpark(Notification $notification): string
    {
        return optional($this->contact)->mobile;
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

    protected function Checkin(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => null == $value ? null :  CheckinData::fromObject(is_array($value) ? $value : json_decode($value, true))
        );
    }
}
