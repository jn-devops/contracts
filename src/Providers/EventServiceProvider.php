<?php

namespace Homeful\Contracts\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Homeful\Contracts\Observers\ContractObserver;
use Homeful\Contracts\Models\Contract;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Contract::observe(ContractObserver::class);
    }
}
