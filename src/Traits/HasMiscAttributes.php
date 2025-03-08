<?php

namespace Homeful\Contracts\Traits;

use Homeful\Contracts\Models\Contract;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
trait HasMiscAttributes
{
    const VOUCHER_CODE ='voucher_code';
    const VOUCHER_SOURCE ='voucher_source';
    public function initializeHasMiscAttributes(): void
    {
        $this->mergeFillable([
            'voucher_code',
            'voucher_source',
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

    public function setVoucherSourceAttribute(string $voucher_source): self
    {
        $this->getAttribute('misc')->set(Contract::VOUCHER_SOURCE, $voucher_source);
        return $this;
    }

    public function getVoucherSourceAttribute(): string{
        $default = null;
        return $this->getAttribute('misc')->get(Contract::VOUCHER_SOURCE) ?? '';
    }

}
