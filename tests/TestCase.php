<?php

namespace Aghfatehi\SaudiFda\Tests;

use Aghfatehi\SaudiFda\SaudiFdaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SaudiFdaServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('saudi-fda.credentials.consumer_key', 'test-consumer-key');
        $app['config']->set('saudi-fda.credentials.consumer_secret', 'test-consumer-secret');
        $app['config']->set('saudi-fda.environment', 'sandbox');
        $app['config']->set('saudi-fda.logging.enabled', false);
    }
}
