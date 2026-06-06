<?php

namespace Aghfatehi\SaudiFda\Services;

use Aghfatehi\SaudiFda\DTO\AccessTokenDTO;
use Aghfatehi\SaudiFda\Exceptions\AuthenticationException;
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Logging\SaudiFdaLogger;
use Illuminate\Support\Facades\Cache;

class AuthService
{
    private string $baseUrl;
    private string $consumerKey;
    private string $consumerSecret;

    public function __construct(
        private ApiClient $client,
        private SaudiFdaLogger $logger,
    ) {
        $this->baseUrl = config('saudi-fda.api.oauth.base', 'https://apis.sfda.gov.sa:9002/v2/oauth');
        $this->consumerKey = config('saudi-fda.credentials.consumer_key', '');
        $this->consumerSecret = config('saudi-fda.credentials.consumer_secret', '');
    }

    public function getAccessToken(bool $forceRefresh = false): AccessTokenDTO
    {
        $cacheEnabled = config('saudi-fda.token_cache.enabled', true);
        $cacheKey = config('saudi-fda.token_cache.key', 'sfda_access_token');

        if (!$forceRefresh && $cacheEnabled) {
            $cached = Cache::store(config('saudi-fda.token_cache.store', 'file'))->get($cacheKey);
            if ($cached instanceof AccessTokenDTO) {
                $this->client->setAccessToken($cached->accessToken);
                return $cached;
            }
        }

        if (empty($this->consumerKey) || empty($this->consumerSecret)) {
            throw new AuthenticationException(
                'SFDA consumer key and secret not configured. Set SFDA_CONSUMER_KEY and SFDA_CONSUMER_SECRET in .env'
            );
        }

        $this->logger->info('Fetching new SFDA access token');

        $response = $this->client->postForm(
            baseUrl: $this->baseUrl,
            endpoint: 'accesstoken?grant_type=client_credentials',
            formData: [],
            customHeaders: [
                'Authorization: Basic ' . base64_encode($this->consumerKey . ':' . $this->consumerSecret),
            ],
        );

        $dto = AccessTokenDTO::fromResponse($response);
        $this->client->setAccessToken($dto->accessToken);

        if ($cacheEnabled) {
            $ttl = (int)$dto->expiresIn - 300;
            Cache::store(config('saudi-fda.token_cache.store', 'file'))
                ->put($cacheKey, $dto, now()->addSeconds(max($ttl, 60)));
        }

        return $dto;
    }

    public function validateCredentials(): bool
    {
        try {
            $this->getAccessToken(true);
            return true;
        } catch (SaudiFdaException) {
            return false;
        }
    }
}
