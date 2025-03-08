<?php

namespace Homeful\Contracts\Traits;

use Homeful\Contracts\Models\Contract;

trait HasMiscAttributes
{
    const VOUCHER_CODE ='voucher_code';
    const PROMO_CODE ='promo_code';
    public function initializeHasMiscAttributes(): void
    {
        $this->mergeFillable([
            'voucher_code',
            'promo_code',
        ]);
    }

    public function setVoucherCodeAttribute(string $voucher_code): self
    {
        $this->getAttribute('meta')->set(Contract::VOUCHER_CODE, $voucher_code);
        return $this;
    }

    public function getVoucherCodeAttribute(): string{
        $default = null;
        return $this->getAttribute('meta')->get(Contract::VOUCHER_CODE) ?? $default;
    }

    public function setPromoCodeAttribute(string $voucher_code): self
    {
        $this->getAttribute('meta')->set(Contract::PROMO_CODE, $voucher_code);
        return $this;
    }

    public function getPromoCodeAttribute(): string{
        $default = null;
        return $this->getAttribute('meta')->get(Contract::PROMO_CODE) ?? $default;
    }

}
