<?php

namespace Homeful\Contracts\Traits;

use Homeful\Contracts\Models\Contract;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
trait HasMiscAttributes
{
    const VOUCHER_CODE ='voucher_code';
    const VOUCHER_SOURCE ='voucher_source';
    const MISC_INPUTS ='misc_inputs';
    public function initializeHasMiscAttributes(): void
    {
        $this->mergeFillable([
            'voucher_code',
            'voucher_source',
            'misc',
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

}
