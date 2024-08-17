<?php

namespace Homeful\Contracts\Observers;

use Homeful\Contracts\Actions\UpdateMortgage;
use Homeful\Contracts\Models\Contract;

class ContractObserver
{
    public function saving(Contract $contract): void
    {
        UpdateMortgage::run($contract);
    }
}
