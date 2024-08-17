<?php

namespace Homeful\Contracts\Traits;

use Homeful\Contracts\Models\Contract;
use Exception;

trait HasInputAttributes
{
    public function setPercentDownPaymentAttribute(float $percent_dp): self
    {
        if ($percent_dp > 0.20) throw new Exception('Maximum percent down payment breached.');
        if ($percent_dp < 0.0) throw new Exception('Minimum percent down payment breached.');

        $this->getAttribute('meta')->set(Contract::PERCENT_DP_FIELD, $percent_dp);

        return $this;
    }

    public function getPercentDownPaymentAttribute(): float
    {
        $default = -1.0;

        return $this->getAttribute('meta')->get(Contract::PERCENT_DP_FIELD) ?? $default;
    }

    public function setPercentMiscellaneousFeesAttribute(float $percent_mf): self
    {
        if ($percent_mf > 0.15) throw new Exception('Maximum percent miscellaneous fees breached.');
        if ($percent_mf < 0.0) throw new Exception('Minimum percent miscellaneous fees breached.');

        $this->getAttribute('meta')->set(Contract::PERCENT_MF_FIELD, $percent_mf);

        return $this;
    }

    public function getPercentMiscellaneousFeesAttribute(): float
    {
        $default = -1.0;

        return $this->getAttribute('meta')->get(Contract::PERCENT_MF_FIELD) ?? $default;
    }

    public function setDownPaymentTermAttribute(float $dp_term): self
    {
        if ($dp_term > 24.0) throw new Exception('Maximum payment term breached.');
        if ($dp_term < 0.0) throw new Exception('Minimum payment term breached.');

        $this->getAttribute('meta')->set(Contract::DP_TERM_FIELD, $dp_term);

        return $this;
    }

    public function getDownPaymentTermAttribute(): float
    {
        $default = -1.0;

        return $this->getAttribute('meta')->get(Contract::DP_TERM_FIELD) ?? $default;
    }

    public function setBalancePaymentTermAttribute(float $bp_term): self
    {
        if ($bp_term > 30.0) throw new Exception('Maximum balance payment term breached.');
        if ($bp_term < 0.0) throw new Exception('Minimum balance payment term breached.');

        $this->getAttribute('meta')->set(Contract::BP_TERM_FIELD, $bp_term);

        return $this;
    }

    public function getBalancePaymentTermAttribute(): float
    {
        $default = -1.0;

        return $this->getAttribute('meta')->get(Contract::BP_TERM_FIELD) ??  $default;;
    }

    public function setInterestRateAttribute(float $bp_interest_rate): self
    {
        if ($bp_interest_rate > 0.2) throw new Exception('Maximum interest rate breached.');
        if ($bp_interest_rate < 0.0) throw new Exception('Minimum interest breached.');

        $this->getAttribute('meta')->set(Contract::BP_INTEREST_RATE_FIELD, $bp_interest_rate);

        return $this;
    }

    public function getInterestRateAttribute(): float
    {
        $default = -1.0;

        return $this->getAttribute('meta')->get(Contract::BP_INTEREST_RATE_FIELD) ?? $default;
    }
}
