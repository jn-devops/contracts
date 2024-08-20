<?php

namespace Homeful\Contracts\Tests;

use Homeful\Mortgage\Providers\EventServiceProvider as MortgageEventServiceProvider;
use Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider;
use Homeful\Contracts\Providers\EventServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\Contracts\ContractsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Homeful\Contacts\ContactsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Homeful\\Contracts\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ContractsServiceProvider::class,
            SchemalessAttributesServiceProvider::class,
            MortgageEventServiceProvider::class,
            ContactsServiceProvider::class,
            EventServiceProvider::class
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        config()->set('data.validation_strategy', 'always');
        config()->set('data.max_transformation_depth', 5);
        config()->set('data.throw_when_max_transformation_depth_reached', 5);

        $migration = include __DIR__.'/../database/migrations/create_contracts_table.php.stub';
        $migration->up();
    }
}
