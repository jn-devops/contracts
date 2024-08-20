<?php

namespace Homeful\Contracts;

use Spatie\LaravelPackageTools\PackageServiceProvider;
use Homeful\Contracts\Commands\ContractsCommand;
use Spatie\LaravelPackageTools\Package;

class ContractsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('contracts')
            ->hasConfigFile(['data', 'mortgage', 'payment'])
            ->hasViews()
            ->hasMigration('create_contracts_table')
            ->hasCommand(ContractsCommand::class);
    }
}
