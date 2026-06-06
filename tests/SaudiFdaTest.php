<?php

namespace Aghfatehi\SaudiFda\Tests;

use Aghfatehi\SaudiFda\Facades\SaudiFda;
use Aghfatehi\SaudiFda\SaudiFdaClient;
use Aghfatehi\SaudiFda\SaudiFdaServiceProvider;
use Orchestra\Testbench\TestCase;

class SaudiFdaTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [SaudiFdaServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'SaudiFda' => SaudiFda::class,
        ];
    }

    public function test_facade_resolves_client()
    {
        $this->assertInstanceOf(SaudiFdaClient::class, SaudiFda::getFacadeRoot());
    }

    public function test_auth_service_is_singleton()
    {
        $client1 = SaudiFda::auth();
        $client2 = SaudiFda::auth();
        $this->assertSame($client1, $client2);
    }

    public function test_services_are_singletons()
    {
        $this->assertSame(SaudiFda::cosmetics(), SaudiFda::cosmetics());
        $this->assertSame(SaudiFda::drugs(), SaudiFda::drugs());
        $this->assertSame(SaudiFda::food(), SaudiFda::food());
        $this->assertSame(SaudiFda::medicalDevices(), SaudiFda::medicalDevices());
    }

    public function test_is_configured_returns_true_with_env()
    {
        config(['saudi-fda.credentials.consumer_key' => 'key']);
        config(['saudi-fda.credentials.consumer_secret' => 'secret']);
        $this->assertTrue(SaudiFda::isConfigured());
    }

    public function test_is_configured_returns_false_without_credentials()
    {
        config(['saudi-fda.credentials.consumer_key' => '']);
        config(['saudi-fda.credentials.consumer_secret' => '']);
        $this->assertFalse(SaudiFda::isConfigured());
    }
}
