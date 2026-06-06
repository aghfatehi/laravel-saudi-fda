<?php

namespace Aghfatehi\SaudiFda\Services;

use Aghfatehi\SaudiFda\DTO\FoodProductDTO;
use Aghfatehi\SaudiFda\Events\ApiRequestFailed;
use Aghfatehi\SaudiFda\Events\ApiRequestSucceeded;
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Logging\SaudiFdaLogger;

class FoodService
{
    private string $baseUrl;

    public function __construct(
        private ApiClient $client,
        private SaudiFdaLogger $logger,
    ) {
        $this->baseUrl = config('saudi-fda.api.food.base', 'https://apis.sfda.gov.sa:9002/v2/Food');
    }

    public function list(array $options = []): object
    {
        $start = microtime(true);
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? null;

        try {
            $query = $limit ? "product/list/{$page}?limit={$limit}" : "product/list/{$page}";
            $response = $this->client->get($this->baseUrl, $query);
            ApiRequestSucceeded::dispatch('food', "product/list/{$page}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('food', "product/list/{$page}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getById(int $productId): FoodProductDTO
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "product/id/{$productId}");
            $dto = FoodProductDTO::fromArray((array)$response->data->result);
            ApiRequestSucceeded::dispatch('food', "product/id/{$productId}", (array)$response, microtime(true) - $start);
            return $dto;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('food', "product/id/{$productId}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getByReferenceNumber(string $referenceNumber): FoodProductDTO
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "product/referencenumber/{$referenceNumber}");
            $dto = FoodProductDTO::fromArray((array)$response->data->result);
            ApiRequestSucceeded::dispatch('food', "product/referencenumber/{$referenceNumber}", (array)$response, microtime(true) - $start);
            return $dto;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('food', "product/referencenumber/{$referenceNumber}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getByBarcode(string $barcode): FoodProductDTO
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "product/barcode/{$barcode}");
            $dto = FoodProductDTO::fromArray((array)$response->data->result);
            ApiRequestSucceeded::dispatch('food', "product/barcode/{$barcode}", (array)$response, microtime(true) - $start);
            return $dto;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('food', "product/barcode/{$barcode}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function search(array $options = []): object
    {
        $start = microtime(true);
        $keyword = $options['keyword'] ?? $options['Keyword'] ?? '';
        $page = $options['page'] ?? 1;

        try {
            $response = $this->client->get($this->baseUrl, "product/search/" . urlencode($keyword) . "/{$page}");
            ApiRequestSucceeded::dispatch('food', "product/search/{$keyword}/{$page}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('food', "product/search/{$keyword}/{$page}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getImage(string $imageCode): object
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "image/{$imageCode}");
            ApiRequestSucceeded::dispatch('food', "image/{$imageCode}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('food', "image/{$imageCode}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }
}
