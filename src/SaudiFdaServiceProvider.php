<?php

namespace Aghfatehi\SaudiFda;

use Aghfatehi\SaudiFda\Commands\SaudiFdaCheckCommand;
use Aghfatehi\SaudiFda\Listeners\LogApiRequestToDatabase;
use Aghfatehi\SaudiFda\Logging\SaudiFdaLogger;
use Aghfatehi\SaudiFda\Services\ApiClient;
use Aghfatehi\SaudiFda\Services\AuthService;
use Aghfatehi\SaudiFda\Services\CosmeticsService;
use Aghfatehi\SaudiFda\Services\DrugService;
use Aghfatehi\SaudiFda\Services\FoodService;
use Aghfatehi\SaudiFda\Services\MedicalDeviceService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class SaudiFdaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/saudi-fda.php', 'saudi-fda');

        $this->app->singleton(SaudiFdaLogger::class, function () {
            return new SaudiFdaLogger;
        });

        $this->app->singleton(ApiClient::class, function ($app) {
            return new ApiClient($app[SaudiFdaLogger::class]);
        });

        $this->app->singleton(AuthService::class, function ($app) {
            $auth = new AuthService($app[ApiClient::class], $app[SaudiFdaLogger::class]);
            $app[ApiClient::class]->setTokenRefreshCallback(function () use ($app, $auth) {
                $auth->getAccessToken(true);
            });
            return $auth;
        });

        $this->app->singleton(CosmeticsService::class, function ($app) {
            return new CosmeticsService($app[ApiClient::class], $app[SaudiFdaLogger::class]);
        });

        $this->app->singleton(DrugService::class, function ($app) {
            return new DrugService($app[ApiClient::class], $app[SaudiFdaLogger::class]);
        });

        $this->app->singleton(FoodService::class, function ($app) {
            return new FoodService($app[ApiClient::class], $app[SaudiFdaLogger::class]);
        });

        $this->app->singleton(MedicalDeviceService::class, function ($app) {
            return new MedicalDeviceService($app[ApiClient::class], $app[SaudiFdaLogger::class]);
        });

        $this->app->singleton(SaudiFdaClient::class, function ($app) {
            return new SaudiFdaClient(
                $app[AuthService::class],
                $app[CosmeticsService::class],
                $app[DrugService::class],
                $app[FoodService::class],
                $app[MedicalDeviceService::class],
            );
        });

        $this->app->alias(SaudiFdaClient::class, 'saudi-fda');
    }

    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerMigrations();
        $this->registerRoutes();
        $this->registerCommands();
        $this->registerEventSubscribers();
    }

    private function registerPublishing(): void
    {
        $this->publishes([
            __DIR__ . '/../config/saudi-fda.php' => config_path('saudi-fda.php'),
        ], 'saudi-fda-config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'saudi-fda-migrations');
    }

    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    private function registerRoutes(): void
    {
        if (config('saudi-fda.routes.enabled', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        }
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SaudiFdaCheckCommand::class,
            ]);
        }
    }

    private function registerEventSubscribers(): void
    {
        Event::subscribe(LogApiRequestToDatabase::class);
    }
}
