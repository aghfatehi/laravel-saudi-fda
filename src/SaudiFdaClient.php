<?php

namespace Aghfatehi\SaudiFda;

use Aghfatehi\SaudiFda\DTO\AccessTokenDTO;
use Aghfatehi\SaudiFda\Enums\Environment;
use Aghfatehi\SaudiFda\Services\AuthService;
use Aghfatehi\SaudiFda\Services\CosmeticsService;
use Aghfatehi\SaudiFda\Services\DrugService;
use Aghfatehi\SaudiFda\Services\FoodService;
use Aghfatehi\SaudiFda\Services\MedicalDeviceService;

class SaudiFdaClient implements \Aghfatehi\SaudiFda\Contracts\SaudiFdaClientInterface
{
    public function __construct(
        private AuthService $auth,
        private CosmeticsService $cosmetics,
        private DrugService $drugs,
        private FoodService $food,
        private MedicalDeviceService $medicalDevices,
    ) {}

    public function auth(): AuthService
    {
        return $this->auth;
    }

    public function cosmetics(): CosmeticsService
    {
        return $this->cosmetics;
    }

    public function drugs(): DrugService
    {
        return $this->drugs;
    }

    public function food(): FoodService
    {
        return $this->food;
    }

    public function medicalDevices(): MedicalDeviceService
    {
        return $this->medicalDevices;
    }

    public function environment(): Environment
    {
        return Environment::tryFrom(config('saudi-fda.environment', 'sandbox')) ?? Environment::Sandbox;
    }

    public function isConfigured(): bool
    {
        return !empty(config('saudi-fda.credentials.consumer_key'))
            && !empty(config('saudi-fda.credentials.consumer_secret'));
    }

    public function isReady(): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $this->auth()->getAccessToken();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
