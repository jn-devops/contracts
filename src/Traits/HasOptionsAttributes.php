<?php

namespace Homeful\Contracts\Traits;

use Homeful\Contracts\Data\LoanTermOptionData;

trait HasOptionsAttributes
{
    const LOAN_TERM_OPTION_FIELD = 'loan_term_option';

    public function initializeHasOptionsAttributes(): void
    {
        $this->mergeFillable([
            'loan_term_option',
        ]);
    }

    public function setLoanTermOptionAttribute(LoanTermOptionData|array $loan_term): self
    {
        $this->getAttribute('meta')->set(self::LOAN_TERM_OPTION_FIELD, $loan_term instanceof LoanTermOptionData ? $loan_term->toArray() : $loan_term);

        return $this;
    }

    public function getLoanTermOptionAttribute(): ?LoanTermOptionData
    {
        $value = $this->getAttribute('meta')->get(self::LOAN_TERM_OPTION_FIELD);

        return $value ? LoanTermOptionData::from($value) : null;
    }
}
