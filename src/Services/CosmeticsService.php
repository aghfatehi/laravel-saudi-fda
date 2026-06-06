<?php

namespace Aghfatehi\SaudiFda\Services;

use Aghfatehi\SaudiFda\DTO\CosmeticsProductDTO;
use Aghfatehi\SaudiFda\Events\ApiRequestFailed;
use Aghfatehi\SaudiFda\Events\ApiRequestSucceeded;
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Logging\SaudiFdaLogger;

class CosmeticsService
{
    private string $baseUrl;

    public function __construct(
        private ApiClient $client,
        private SaudiFdaLogger $logger,
    ) {
        $this->baseUrl = config('saudi-fda.api.cosmetics.base', 'https://apis.sfda.gov.sa:9002/v2/cosmetics');
    }

    public function list(int $page = 1, ?string $keyword = null): object
    {
        $start = microtime(true);
        $query = ['page' => $page];
        if ($keyword !== null) {
            $query['Keyword'] = $keyword;
        }

        try {
            $response = $this->client->get($this->baseUrl, 'list', $query);
            ApiRequestSucceeded::dispatch('cosmetics', 'list', (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('cosmetics', 'list', $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getById(int $productId): CosmeticsProductDTO
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "Product_Id/{$productId}");
            $dto = CosmeticsProductDTO::fromArray((array)$response->data);
            ApiRequestSucceeded::dispatch('cosmetics', "Product_Id/{$productId}", (array)$response, microtime(true) - $start);
            return $dto;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('cosmetics', "Product_Id/{$productId}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getByCosmeticNumber(string $cosmeticNumber): CosmeticsProductDTO
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "cosmeticNumber/{$cosmeticNumber}");
            $dto = CosmeticsProductDTO::fromArray((array)$response->data);
            ApiRequestSucceeded::dispatch('cosmetics', "cosmeticNumber/{$cosmeticNumber}", (array)$response, microtime(true) - $start);
            return $dto;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('cosmetics', "cosmeticNumber/{$cosmeticNumber}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getByBarcode(string $barcode): CosmeticsProductDTO
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "BarCode/{$barcode}");
            $dto = CosmeticsProductDTO::fromArray((array)$response->data);
            ApiRequestSucceeded::dispatch('cosmetics', "BarCode/{$barcode}", (array)$response, microtime(true) - $start);
            return $dto;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('cosmetics', "BarCode/{$barcode}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function search(?string $specificNameAr = null, ?string $specificName = null, ?string $brandName = null, ?string $barCode = null, ?string $cosmeticNumber = null, int $page = 1): object
    {
        $start = microtime(true);
        $query = array_filter([
            'SpecificNameAr' => $specificNameAr,
            'SpecificName' => $specificName,
            'BrandName' => $brandName,
            'barCode' => $barCode,
            'CosmeticNumber' => $cosmeticNumber,
            'page' => $page,
        ], fn($v) => $v !== null);

        try {
            $response = $this->client->get($this->baseUrl, 'search', $query);
            ApiRequestSucceeded::dispatch('cosmetics', 'search', (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('cosmetics', 'search', $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function searchByKeyword(string $keyword, int $page = 1): object
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "search/{$keyword}/{$page}");
            ApiRequestSucceeded::dispatch('cosmetics', "search/{$keyword}/{$page}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('cosmetics', "search/{$keyword}/{$page}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getImage(string $imageCode): object
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "image/{$imageCode}");
            ApiRequestSucceeded::dispatch('cosmetics', "image/{$imageCode}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('cosmetics', "image/{$imageCode}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }
}
