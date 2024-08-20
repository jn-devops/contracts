<?php

namespace Homeful\Contracts\Observers;

use Homeful\Contracts\Actions\UpdateContractMortgageAttribute;
use Homeful\Contracts\Models\Contract;

class ContractObserver
{
    public function saving(Contract $contract): void
    {
        UpdateContractMortgageAttribute::run($contract);
    }
}
