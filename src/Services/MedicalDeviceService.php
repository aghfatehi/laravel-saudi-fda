<?php

namespace Aghfatehi\SaudiFda\Services;

use Aghfatehi\SaudiFda\DTO\MedicalDeviceDTO;
use Aghfatehi\SaudiFda\Events\ApiRequestFailed;
use Aghfatehi\SaudiFda\Events\ApiRequestSucceeded;
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Logging\SaudiFdaLogger;

class MedicalDeviceService
{
    private string $baseUrl;

    public function __construct(
        private ApiClient $client,
        private SaudiFdaLogger $logger,
    ) {
        $this->baseUrl = config('saudi-fda.api.medical_devices.base', 'https://apis.sfda.gov.sa:9002/v2/dwh-md');
    }

    public function listLowRisk(array $options = []): object
    {
        $start = microtime(true);
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? null;

        try {
            $query = $limit ? "Lowrisk/list/{$page}?limit={$limit}" : "Lowrisk/list/{$page}";
            $response = $this->client->get($this->baseUrl, $query);
            ApiRequestSucceeded::dispatch('medical_devices', "Lowrisk/list/{$page}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', "Lowrisk/list/{$page}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getLowRiskProduct(?int $lowRiskId = null, ?int $productId = null, ?string $accountNumber = null, ?string $registrationNumber = null, ?string $crNumber = null): MedicalDeviceDTO
    {
        $start = microtime(true);
        $query = array_filter([
            'LowRiskID' => $lowRiskId,
            'productID' => $productId,
            'AccountNumber' => $accountNumber,
            'RegistrationNumber' => $registrationNumber,
            'CrNumber' => $crNumber,
        ], fn($v) => $v !== null);

        try {
            $response = $this->client->get($this->baseUrl, 'Lowrisk/Product', $query);
            $dto = MedicalDeviceDTO::fromLowRiskData((array)$response->data);
            ApiRequestSucceeded::dispatch('medical_devices', 'Lowrisk/Product', (array)$response, microtime(true) - $start);
            return $dto;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', 'Lowrisk/Product', $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function searchLowRisk(string $keyword, int $page = 1): object
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "Lowrisk/search/" . urlencode($keyword) . "/{$page}");
            ApiRequestSucceeded::dispatch('medical_devices', "Lowrisk/search/{$keyword}/{$page}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', "Lowrisk/search/{$keyword}/{$page}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function listGHTF(array $options = []): object
    {
        $start = microtime(true);
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? null;

        try {
            $query = $limit ? "GHTF/list/{$page}?limit={$limit}" : "GHTF/list/{$page}";
            $response = $this->client->get($this->baseUrl, $query);
            ApiRequestSucceeded::dispatch('medical_devices', "GHTF/list/{$page}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', "GHTF/list/{$page}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getGHTFProduct(?int $propertiesId = null, ?int $mdId = null, ?string $referenceNumber = null, ?string $accountNumber = null, ?string $deviceNumber = null, ?string $crNumber = null): MedicalDeviceDTO
    {
        $start = microtime(true);
        $query = array_filter([
            'PropertiesId' => $propertiesId,
            'MDId' => $mdId,
            'ReferenceNumber' => $referenceNumber,
            'AccountNumber' => $accountNumber,
            'DeviceNumber' => $deviceNumber,
            'CrNumber' => $crNumber,
        ], fn($v) => $v !== null);

        try {
            $response = $this->client->get($this->baseUrl, 'GHTF/Product', $query);
            $dto = MedicalDeviceDTO::fromGHTFData((array)$response->data);
            ApiRequestSucceeded::dispatch('medical_devices', 'GHTF/Product', (array)$response, microtime(true) - $start);
            return $dto;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', 'GHTF/Product', $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getGHTFAccessory(int $propertiesId): object
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "GHTF/Accessory/id/{$propertiesId}");
            ApiRequestSucceeded::dispatch('medical_devices', "GHTF/Accessory/id/{$propertiesId}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', "GHTF/Accessory/id/{$propertiesId}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function searchGHTF(string $keyword, int $page = 1): object
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "GHTF/search/" . urlencode($keyword) . "/{$page}");
            ApiRequestSucceeded::dispatch('medical_devices', "GHTF/search/{$keyword}/{$page}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', "GHTF/search/{$keyword}/{$page}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function listTFA(array $options = []): object
    {
        $start = microtime(true);
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? null;

        try {
            $query = $limit ? "TFA/list/{$page}?limit={$limit}" : "TFA/list/{$page}";
            $response = $this->client->get($this->baseUrl, $query);
            ApiRequestSucceeded::dispatch('medical_devices', "TFA/list/{$page}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', "TFA/list/{$page}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function getTFAAccessory(int $propertiesId): object
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "TFA/Accessory/id/{$propertiesId}");
            ApiRequestSucceeded::dispatch('medical_devices', "TFA/Accessory/id/{$propertiesId}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', "TFA/Accessory/id/{$propertiesId}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }

    public function searchTFA(string $keyword, int $page = 1): object
    {
        $start = microtime(true);

        try {
            $response = $this->client->get($this->baseUrl, "TFA/search/" . urlencode($keyword) . "/{$page}");
            ApiRequestSucceeded::dispatch('medical_devices', "TFA/search/{$keyword}/{$page}", (array)$response, microtime(true) - $start);
            return $response;
        } catch (SaudiFdaException $e) {
            ApiRequestFailed::dispatch('medical_devices', "TFA/search/{$keyword}/{$page}", $e->getMessage(), $e->getCode());
            throw $e;
        }
    }
}
