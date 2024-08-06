<?php

namespace Homeful\Contracts\Commands;

use Illuminate\Console\Command;

class ContractsCommand extends Command
{
    public $signature = 'contracts';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
