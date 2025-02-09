<?php

namespace Homeful\Contracts\Models;

use Homeful\Contacts\Classes\{AddressMetadata, AIFMetadata, CoBorrowerMetadata, ContactMetaData, EmploymentMetadata, SpouseMetadata};
use Propaganistas\LaravelPhone\Exceptions\CountryCodeException;
use Homeful\Contacts\Enums\{CivilStatus, Nationality, Sex};
use Homeful\Contacts\Models\Contact as BaseContact;
use Spatie\LaravelData\{DataCollection, WithData};
use Illuminate\Database\Eloquent\Casts\Attribute;
use DateTimeInterface;

/**
 * Class Contact
 *
 * @method ContactMetaData getData()
 */
class Contact extends BaseContact
{
    use WithData;

    protected string $dataClass = ContactMetaData::class;

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    protected function casts(): array
    {
        return [
            'sex' => Sex::class,
            'civil_status' => CivilStatus::class,
            'nationality' => Nationality::class,
            'addresses' => DataCollection::class . ':' . AddressMetadata::class,
            'employment' => DataCollection::class . ':' . EmploymentMetadata::class,
            'spouse' => SpouseMetadata::class,
            'co_borrowers' => DataCollection::class . ':' . CoBorrowerMetadata::class,
            'order' => AIFMetadata::class,
        ];
    }

    protected function Mobile(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => phone($value, 'PH')->formatForMobileDialingInCountry('PH'),
            set: fn ($value) => phone($value, 'PH')->formatForMobileDialingInCountry('PH')
        );
    }

    /**
     * @throws CountryCodeException
     */
    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $value = phone($value, 'PH')->formatForMobileDialingInCountry('PH');
        }
        catch (\Exception $e) {}

        return $this->where('mobile', $value)->firstOrFail();
    }
}
