<?php

namespace Homeful\Contracts\Traits;

use Homeful\Contracts\Models\Contract;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Illuminate\Database\Eloquent\Builder;
trait HasMiscAttributes
{
    const VOUCHER_CODE ='voucher_code';
    const PROMO_CODE ='promo_code';
    public function initializeHasMiscAttributes(): void
    {
        $this->mergeFillable([
            'voucher_code',
            'promo_code',
            'misc'
        ]);
        $this->mergeCasts([
            'misc' => SchemalessAttributes::class,
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

    public function setPromoCodeAttribute(string $voucher_code): self
    {
        $this->getAttribute('misc')->set(Contract::PROMO_CODE, $voucher_code);
        return $this;
    }

    public function getPromoCodeAttribute(): string{
        $default = null;
        return $this->getAttribute('misc')->get(Contract::PROMO_CODE) ?? '';
    }

}
