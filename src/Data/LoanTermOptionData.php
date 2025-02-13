<?php

namespace Homeful\Contracts\Data;

use Spatie\LaravelData\Data;

class LoanTermOptionData extends Data
{
    public function __construct(
        public string $term_1,
        public string $term_2,
        public string $term_3,
        public int $months_term,
        public int $loanable_years,
    ){}
}
