<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\States\Consulted;
use Homeful\Contracts\Models\Contract;

class PendingToConsulted extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Consulted($this->contract);
        $this->contract->consulted = true;
        $this->contract->save();

        return parent::handle();
    }
}
