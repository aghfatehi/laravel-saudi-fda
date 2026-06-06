<?php

namespace Aghfatehi\SaudiFda\Services;

use Aghfatehi\SaudiFda\DTO\DrugProductDTO;
use Aghfatehi\SaudiFda\Events\ApiRequestFailed;
use Aghfatehi\SaudiFda\Events\ApiRequestSucceeded;
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Logging\SaudiFdaLogger;

class DrugService
{
    private string $baseUrl;

    public function __construct(
        private ApiClient $client,
        private SaudiFdaLogger $logger,
    ) {
        $this->baseUrl = config('saudi-fda.api.drugs.base', 'https://apis.sfda.gov.sa:9002/v2/DMS');
    }

    public function list(int $page = 1): object
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, 'drug/list', ['page' => $page]);
            ApiRequestSucceeded::dispatch('drugs', 'drug/list', (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('drugs', 'drug/list', $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function toDto(object $item): DrugProductDTO
    {
        return DrugProductDTO::fromArray((array)$item);
    }
}
