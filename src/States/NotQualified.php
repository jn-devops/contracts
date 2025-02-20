<?php

namespace Homeful\Contracts\States;

class NotQualified extends ContractState
{
    public function name(): string
    {
        return 'not qualified';
    }

    public function color(): string
    {
        return 'danger';
    }
    public function icon(): string
    {
        return 'heroicon-m-arrow-path';
    }
}
