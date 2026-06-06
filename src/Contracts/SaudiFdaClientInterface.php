<?php

namespace Aghfatehi\SaudiFda\Contracts;

use Aghfatehi\SaudiFda\DTO\AccessTokenDTO;
use Aghfatehi\SaudiFda\Enums\Environment;
use Aghfatehi\SaudiFda\Services\AuthService;
use Aghfatehi\SaudiFda\Services\CosmeticsService;
use Aghfatehi\SaudiFda\Services\DrugService;
use Aghfatehi\SaudiFda\Services\FoodService;
use Aghfatehi\SaudiFda\Services\MedicalDeviceService;

interface SaudiFdaClientInterface
{
    public function auth(): AuthService;
    public function cosmetics(): CosmeticsService;
    public function drugs(): DrugService;
    public function food(): FoodService;
    public function medicalDevices(): MedicalDeviceService;
    public function environment(): Environment;
    public function isConfigured(): bool;
    public function isReady(): bool;
}
