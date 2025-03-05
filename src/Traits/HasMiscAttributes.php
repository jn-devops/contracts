<?php

namespace Homeful\Contracts\Traits;

use Homeful\Contracts\Models\Contract;

trait HasMiscAttributes
{
    const VOUCHER_CODE ='voucher_code';
    public function initializeHasMiscAttributes(): void
    {
        $this->mergeFillable([
            'voucher_code'
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

}
