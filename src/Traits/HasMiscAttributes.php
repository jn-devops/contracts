<?php

namespace Homeful\Contracts\Traits;

use Homeful\Contracts\Models\Contract;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
trait HasMiscAttributes
{
    const VOUCHER_CODE ='voucher_code';
    const VOUCHER_SOURCE ='voucher_source';

    const SOURCE_OF_SALE = 'source_of_sale';
    const MISC_INPUTS ='misc_inputs';
    const REFERRAL_CODE ='referral_code';

    const CAMPAIGN_AUTHOR ='campaign_author';
    const CAMPAIGN_CODE ='campaign_code';
    public function initializeHasMiscAttributes(): void
    {
        $this->mergeFillable([
            'voucher_code',
            'voucher_source',
            'misc_inputs',
            'misc',
            'source_of_sale',
            'referral_code',
            'campaign_author',
            'campaign_code',
            Contract::MISC_INPUTS,
        ]);
        $this->mergeCasts([
            'misc' => SchemalessAttributes::class,
            Contract::MISC_INPUTS => 'array',
        ]);
    }

    public function scopeMiscAttributes(): Builder
    {
        return $this->misc->modelScope();
    }

    public function setVoucherCodeAttribute(string $voucher_code): self
    {
        $this->getAttribute('misc')->set(Contract::VOUCHER_CODE, $voucher_code);
        return $this;
    }

    public function getVoucherCodeAttribute(): string{
        $default = '';
        return $this->getAttribute('misc')->get(Contract::VOUCHER_CODE) ?? $default;
    }

    public function setVoucherSourceAttribute(string $voucher_source): self
    {
        $this->getAttribute('misc')->set(Contract::VOUCHER_SOURCE, $voucher_source);
        return $this;
    }

    public function getVoucherSourceAttribute(): string{
        return $this->getAttribute('misc')->get(Contract::VOUCHER_SOURCE) ?? '';
    }

    public function setMiscInputsAttribute(array $inputs): self
    {
        $this->getAttribute('misc')->set(Contract::MISC_INPUTS, $inputs);
        return $this;
    }

    public function getMiscInputsAttribute(): array{
        return $this->getAttribute('misc')->get(Contract::MISC_INPUTS) ?? [];
    }

    public function getSourceOfSaleAttribute(): string{
        return $this->getAttribute('misc')->get(Contract::SOURCE_OF_SALE) ?? '';
    }
    public function setSourceOfSaleAttribute(string $source_of_sale): self
    {
        $this->getAttribute('misc')->set(Contract::SOURCE_OF_SALE, $source_of_sale);
        return $this;
    }
    public function getReferralCodeAttribute(): string{
        return $this->getAttribute('misc')->get(Contract::REFERRAL_CODE) ?? '';
    }
    public function setReferralCodeAttribute(string $referral_code): self
    {
        $this->getAttribute('misc')->set(Contract::REFERRAL_CODE, $referral_code);
        return $this;
    }

    public function getCampaignAuthorAttribute(): string{
        return $this->getAttribute('misc')->get(Contract::CAMPAIGN_AUTHOR) ?? '';
    }
    public function setCampaignAuthorAttribute(string $campaign_author): self
    {
        $this->getAttribute('misc')->set(Contract::CAMPAIGN_AUTHOR, $campaign_author);
        return $this;
    }

    public function getCampaignCodeAttribute(): string{
        return $this->getAttribute('misc')->get(Contract::CAMPAIGN_CODE) ?? '';
    }

    public function setCampaignCodeAttribute(string $campaign_code): self{
        $this->getAttribute('misc')->set(Contract::CAMPAIGN_CODE, $campaign_code);
        return $this;
    }
}
