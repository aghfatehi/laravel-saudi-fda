<?php

use Aghfatehi\SaudiFda\SaudiFdaClient;
use Illuminate\Support\Facades\Route;

$prefix = config('saudi-fda.routes.prefix', 'api/saudi-fda');
$middleware = config('saudi-fda.routes.middleware', 'api');

Route::prefix($prefix)->middleware($middleware)->group(function () {
    Route::get('status', function (SaudiFdaClient $client) {
        return response()->json([
            'status' => 'ok',
            'environment' => $client->environment()->value,
            'configured' => $client->isConfigured(),
            'ready' => $client->isConfigured(),
            'timestamp' => now()->toIso8601String(),
        ]);
    })->name('saudi-fda.status');

    Route::prefix('auth')->group(function () {
        Route::post('token', function (SaudiFdaClient $client) {
            $token = $client->auth()->getAccessToken(true);
            return response()->json([
                'access_token' => $token->accessToken,
                'expires_in' => $token->expiresIn,
                'token_type' => 'Bearer',
            ]);
        })->name('saudi-fda.auth.token');
    });

    Route::prefix('cosmetics')->group(function () {
        Route::get('/', function (SaudiFdaClient $client) {
            $data = $client->cosmetics()->list(request()->all());
            return response()->json($data);
        })->name('saudi-fda.cosmetics.list');

        Route::get('{id}', function (string $id, SaudiFdaClient $client) {
            $data = $client->cosmetics()->getById((int) $id);
            return response()->json($data);
        })->name('saudi-fda.cosmetics.by-id');

        Route::get('number/{cosmeticNumber}', function (string $cosmeticNumber, SaudiFdaClient $client) {
            $data = $client->cosmetics()->getByCosmeticNumber($cosmeticNumber);
            return response()->json($data);
        })->name('saudi-fda.cosmetics.by-number');

        Route::get('barcode/{barcode}', function (string $barcode, SaudiFdaClient $client) {
            $data = $client->cosmetics()->getByBarcode($barcode);
            return response()->json($data);
        })->name('saudi-fda.cosmetics.by-barcode');

        Route::post('search', function (SaudiFdaClient $client) {
            $data = $client->cosmetics()->search(request()->all());
            return response()->json($data);
        })->name('saudi-fda.cosmetics.search');
    });

    Route::prefix('drugs')->group(function () {
        Route::get('/', function (SaudiFdaClient $client) {
            $data = $client->drugs()->list(request()->all());
            return response()->json($data);
        })->name('saudi-fda.drugs.list');
    });

    Route::prefix('food')->group(function () {
        Route::get('/', function (SaudiFdaClient $client) {
            $data = $client->food()->list(request()->all());
            return response()->json($data);
        })->name('saudi-fda.food.list');

        Route::get('{id}', function (string $id, SaudiFdaClient $client) {
            $data = $client->food()->getById((int) $id);
            return response()->json($data);
        })->name('saudi-fda.food.by-id');

        Route::post('search', function (SaudiFdaClient $client) {
            $data = $client->food()->search(request()->all());
            return response()->json($data);
        })->name('saudi-fda.food.search');
    });

    Route::prefix('medical-devices')->group(function () {
        Route::get('low-risk', function (SaudiFdaClient $client) {
            $data = $client->medicalDevices()->listLowRisk(request()->all());
            return response()->json($data);
        })->name('saudi-fda.medical-devices.low-risk');

        Route::get('ghtf', function (SaudiFdaClient $client) {
            $data = $client->medicalDevices()->listGHTF(request()->all());
            return response()->json($data);
        })->name('saudi-fda.medical-devices.ghtf');

        Route::get('tfa', function (SaudiFdaClient $client) {
            $data = $client->medicalDevices()->listTFA(request()->all());
            return response()->json($data);
        })->name('saudi-fda.medical-devices.tfa');
    });
});
