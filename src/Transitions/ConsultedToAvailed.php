<?php

namespace Homeful\Contracts\Transitions;

use Homeful\Contracts\Models\Contract;
use Homeful\Contracts\States\Availed;

class ConsultedToAvailed extends ContractTransition
{
    public function handle(): Contract
    {
        $this->contract->state = new Availed($this->contract);
        $this->contract->availed = true;
        $this->contract->save();

        return parent::handle();
    }
}
