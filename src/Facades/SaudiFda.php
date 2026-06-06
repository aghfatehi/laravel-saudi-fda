<?php

namespace Aghfatehi\SaudiFda\Facades;

use Aghfatehi\SaudiFda\SaudiFdaClient;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Aghfatehi\SaudiFda\Services\AuthService auth()
 * @method static \Aghfatehi\SaudiFda\Services\CosmeticsService cosmetics()
 * @method static \Aghfatehi\SaudiFda\Services\DrugService drugs()
 * @method static \Aghfatehi\SaudiFda\Services\FoodService food()
 * @method static \Aghfatehi\SaudiFda\Services\MedicalDeviceService medicalDevices()
 * @method static \Aghfatehi\SaudiFda\Enums\Environment environment()
 * @method static bool isConfigured()
 * @method static bool isReady()
 *
 * @see \Aghfatehi\SaudiFda\SaudiFdaClient
 */
class SaudiFda extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'saudi-fda';
    }
}
