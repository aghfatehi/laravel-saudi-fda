<?php

namespace Aghfatehi\SaudiFda\Tests\Unit;

use Aghfatehi\SaudiFda\Exceptions\AuthenticationException;
use Aghfatehi\SaudiFda\SaudiFdaClient;
use Aghfatehi\SaudiFda\Tests\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_get_access_token_throws_without_credentials()
    {
        config(['saudi-fda.credentials.consumer_key' => '']);
        config(['saudi-fda.credentials.consumer_secret' => '']);

        $client = $this->app->make(SaudiFdaClient::class);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('SFDA consumer key and secret not configured');

        $client->auth()->getAccessToken();
    }
}
