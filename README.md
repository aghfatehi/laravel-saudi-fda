<!--
 Author: AL-AGHBARI Fatehi
 Website: https://fsoftdev.com | https://github.com/aghfatehi
 Company: FsoftDev.com
 Package: aghfatehi/laravel-saudi-fda
 SEO: Saudi FDA API Laravel, SFDA Laravel Integration, SFDA API PHP Laravel
-->

<p align="center">
 <a href="https://www.php.net/"><img src="https://img.shields.io/badge/php-^8.1-8892BF.svg?style=for-the-badge&logo=php" alt="PHP Version"></a>
 <a href="https://laravel.com/"><img src="https://img.shields.io/badge/Laravel-9|10|11|12|13-FF2D20.svg?style=for-the-badge&logo=laravel" alt="Laravel Version"></a>
 <a href="https://sfda.gov.sa/"><img src="https://img.shields.io/badge/SFDA-Cosmetics_%2B_Drugs_%2B_Food_%2B_Medical_Devices-00A859.svg?style=for-the-badge" alt="SFDA Services"></a>
 <a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge" alt="License"></a>
 <a href="https://github.com/aghfatehi/laravel-saudi-fda/actions"><img src="https://img.shields.io/github/actions/workflow/status/aghfatehi/laravel-saudi-fda/laravel.yml?style=for-the-badge&label=Tests" alt="Tests"></a>
 <a href="https://packagist.org/packages/aghfatehi/laravel-saudi-fda"><img src="https://img.shields.io/packagist/v/aghfatehi/laravel-saudi-fda.svg?style=for-the-badge" alt="Packagist"></a>
 <a href="https://packagist.org/packages/aghfatehi/laravel-saudi-fda"><img src="https://img.shields.io/packagist/dt/aghfatehi/laravel-saudi-fda.svg?style=for-the-badge" alt="Downloads"></a>
 <a href="https://fsoftdev.com"><img src="https://img.shields.io/badge/FsoftDev-FsoftDev.com-blue.svg?style=for-the-badge" alt="FsoftDev"></a>
 <a href="https://github.com/aghfatehi"><img src="https://img.shields.io/badge/Author-AL--AGHBARI%20Fatehi-blue.svg?style=for-the-badge" alt="Author"></a>
</p>

<h1 align="center">Saudi FDA (SFDA) API Integration for Laravel</h1>
<h3 align="center">Laravel package for the Saudi Food and Drug Authority public APIs — Cosmetics, Drugs, Food, Medical Devices</h3>
<h4 align="center">By <a href="https://fsoftdev.com">FsoftDev.com</a> — <a href="https://github.com/aghfatehi">AL-AGHBARI Fatehi</a></h4>

<p align="center">
 <strong>SFDA integration for Laravel — automatic OAuth2 authentication, cosmetics & drug & food & medical device APIs</strong>
</p>

---

## Table of Contents

- [Requirements & Installation](#requirements--installation)
- [Configuration](#configuration)
- [Quick Start](#quick-start)
- [Usage](#usage)
  - [Authentication](#authentication)
  - [Cosmetics API](#cosmetics-api)
  - [Drugs API](#drugs-api)
  - [Food API](#food-api)
  - [Medical Devices API](#medical-devices-api)
- [API Routes](#api-routes)
- [Error Handling](#error-handling)
- [Events](#events)
- [Artisan Commands](#artisan-commands)
- [Testing](#testing)
- [Postman Collection](#postman-collection)
- [License](#license)

---

## Requirements & Installation

| Requirement | Version |
|---|---|
| **PHP** | `^8.1` |
| **Laravel** | `9.x` · `10.x` · `11.x` · `12.x` · `13.x` |
| **Extensions** | `json`, `curl` |

```bash
composer require aghfatehi/laravel-saudi-fda
```

Auto-discovery is enabled — no manual service provider registration needed.

Publish the configuration (optional):

```bash
php artisan vendor:publish --tag=saudi-fda-config
```

---

## Configuration

Add to your `.env` file:

```env
SFDA_CONSUMER_KEY=your_consumer_key
SFDA_CONSUMER_SECRET=your_consumer_secret
SFDA_ENVIRONMENT=sandbox
```

### All Configuration Options

| Variable | Default | Required | Description |
|---|---|---|---|
| `SFDA_CONSUMER_KEY` | — | Yes | Your SFDA Consumer Key |
| `SFDA_CONSUMER_SECRET` | — | Yes | Your SFDA Consumer Secret |
| `SFDA_ENVIRONMENT` | `sandbox` | No | `sandbox` or `production` |
| `SFDA_TOKEN_CACHE_ENABLED` | `true` | No | Cache the OAuth2 access token |
| `SFDA_TOKEN_CACHE_STORE` | `file` | No | Cache driver (file, redis, memcached, etc.) |
| `SFDA_TOKEN_CACHE_KEY` | `sfda_access_token` | No | Custom cache key for the token |
| `SFDA_API_TIMEOUT` | `60` | No | HTTP request timeout in seconds |
| `SFDA_ROUTES_ENABLED` | `true` | No | Enable/disable built-in API routes |
| `SFDA_ROUTES_PREFIX` | `api/saudi-fda` | No | URI prefix for built-in routes |
| `SFDA_LOGGING_ENABLED` | `true` | No | Enable API call logging |
| `SFDA_LOG_LEVEL` | `info` | No | Log level (debug, info, notice, warning, error) |
| `SFDA_LOG_DATABASE_ENABLED` | `false` | No | Log API requests to `sfda_api_logs` table |
| `SFDA_LOG_DATABASE_CONNECTION` | — | No | Database connection for logging (defaults to your default DB) |

### Override Base URLs (optional)

Each service base URL can be overridden individually:

| Variable | Default |
|---|---|
| `SFDA_OAUTH_BASE` | `https://apis.sfda.gov.sa:9002/v2/oauth` |
| `SFDA_COSMETICS_BASE` | `https://apis.sfda.gov.sa:9002/v2/cosmetics` |
| `SFDA_DRUGS_BASE` | `https://apis.sfda.gov.sa:9002/v2/DMS` |
| `SFDA_FOOD_BASE` | `https://apis.sfda.gov.sa:9002/v2/Food` |
| `SFDA_MEDICAL_DEVICES_BASE` | `https://apis.sfda.gov.sa:9002/v2/dwh-md` |

---

## Quick Start

```bash
# Full health check — config + authentication + API connectivity
php artisan saudi-fda:check

# View configuration (credentials masked)
php artisan saudi-fda:check --config
```

### Using the Facade

```php
use Aghfatehi\SaudiFda\Facades\SaudiFda;

SaudiFda::isConfigured();   // bool — credentials present in config
SaudiFda::isReady();        // bool — credentials valid, token obtained
SaudiFda::environment();    // \Aghfatehi\SaudiFda\Enums\Environment
```

### Dependency Injection

```php
use Aghfatehi\SaudiFda\SaudiFdaClient;

class ProductController extends Controller
{
    public function __construct(private SaudiFdaClient $sfda) {}

    public function show($barcode)
    {
        return $this->sfda->cosmetics()->getByBarcode($barcode);
    }
}
```

---

### Token Storage & Cache

The access token is automatically cached using Laravel's cache system to avoid requesting a new token on every API call.

**How it works:**

1. On first API call, the package requests an OAuth2 token from SFDA
2. The token (as an `AccessTokenDTO`) is stored in the cache with a TTL of `expiresIn - 300` seconds (5-minute safety margin)
3. Subsequent calls check the cache first — if a valid `AccessTokenDTO` is found, it's reused
4. If a cached token exists but is expired, or if `forceRefresh` is used, a new token is fetched and the cache is updated
5. If any API call receives a **401 Unauthorized**, the package automatically refreshes the token and retries the request once

**Cache configuration via `.env`:**

```env
SFDA_TOKEN_CACHE_ENABLED=true        # Enable/disable token caching
SFDA_TOKEN_CACHE_STORE=file          # Cache driver (file, redis, memcached, database)
SFDA_TOKEN_CACHE_KEY=sfda_access_token  # Cache key name
```

**Example — force refresh token:**

```php
use Aghfatehi\SaudiFda\Facades\SaudiFda;

// Bypass cache, always get a fresh token
$token = SaudiFda::auth()->getAccessToken(true);

// Token details
$token->accessToken;  // string — the Bearer token
$token->expiresIn;    // int — seconds until expiry (typically 86400)
$token->tokenType;    // string — "Bearer"
```

**Example — clear cached token manually:**

```php
use Illuminate\Support\Facades\Cache;

Cache::store(config('saudi-fda.token_cache.store', 'file'))
    ->forget(config('saudi-fda.token_cache.key', 'sfda_access_token'));
```

**Example — use a different cache store (Redis example):**

```env
SFDA_TOKEN_CACHE_STORE=redis
```

The package stores a serialized `AccessTokenDTO` object. Any Laravel cache driver that supports serialization works out of the box.

**How auto-refresh works:**

```
Request -> 401 Unauthorized -> Package auto-refreshes token -> Retries request -> Succeeds
```

This happens transparently in `ApiClient` — the method `tokenRefreshCallback` is called when a 401 is detected, and the request is retried once.

---

## Usage

All methods use the `SaudiFda` facade to access the four service groups:

```php
use Aghfatehi\SaudiFda\Facades\SaudiFda;

SaudiFda::cosmetics();       // CosmeticsService
SaudiFda::drugs();           // DrugService
SaudiFda::food();            // FoodService
SaudiFda::medicalDevices();  // MedicalDeviceService
```

---

### Authentication

The package handles **OAuth2 Client Credentials** automatically — tokens are obtained, cached, and refreshed transparently. If any API call receives a 401 response, the package automatically requests a new token and retries once.

**SFDA Endpoint:** `POST /v2/oauth/accesstoken?grant_type=client_credentials`
**Auth:** HTTP Basic (`Consumer Key : Consumer Secret`)
**Token Expiry:** 86400 seconds (24 hours)

```php
use Aghfatehi\SaudiFda\Facades\SaudiFda;

// Get token (uses cache if available)
$token = SaudiFda::auth()->getAccessToken();
$token->accessToken;  // string — the Bearer token
$token->expiresIn;    // int — seconds until expiry

// Force a fresh token (bypass cache)
$token = SaudiFda::auth()->getAccessToken(true);

// Check credentials validity
SaudiFda::auth()->validateCredentials(); // bool
```

---

### Cosmetics API

**Base URL:** `https://apis.sfda.gov.sa:9002/v2/cosmetics`

#### 1. `list(array $options = [])`

Paginated list of cosmetic products.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `page` | int | No | 1 | Page number |
| `limit` | int | No | — | Results per page |
| `Keyword` | string | No | — | Filter by keyword |

```php
SaudiFda::cosmetics()->list(['page' => 1, 'limit' => 50]);
SaudiFda::cosmetics()->list(['Keyword' => 'cream']);
SaudiFda::cosmetics()->list(['page' => 2, 'limit' => 20, 'Keyword' => 'lotion']);
```

**SFDA Endpoint:** `GET /v2/cosmetics/list?page=&limit=&Keyword=`

---

#### 2. `getById(int $productId)`

Get a single cosmetic product by its SFDA product ID.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `productId` | int | Yes | — | SFDA product identifier |

```php
SaudiFda::cosmetics()->getById(1495);
```

**SFDA Endpoint:** `GET /v2/cosmetics/Product_Id/{productID}`

---

#### 3. `getByCosmeticNumber(string $cosmeticNumber)`

Get a cosmetic product by its registration number.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `cosmeticNumber` | string | Yes | — | Cosmetic registration number (e.g., `CN-2023-08203`) |

```php
SaudiFda::cosmetics()->getByCosmeticNumber('CN-2023-08203');
```

**SFDA Endpoint:** `GET /v2/cosmetics/cosmeticNumber/{cosmeticNumber}`

---

#### 4. `getByBarcode(string $barcode)`

Get a cosmetic product by its barcode (EAN/UPC).

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `barcode` | string | Yes | — | Product barcode |

```php
SaudiFda::cosmetics()->getByBarcode('6281007990215');
```

**SFDA Endpoint:** `GET /v2/cosmetics/BarCode/{barcode}`

---

#### 5. `search(array $options = [])`

Advanced search across multiple cosmetic product fields. All parameters are optional — filtered results include only the fields you supply.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `SpecificNameAr` | string | No | — | Arabic specific name |
| `SpecificName` | string | No | — | English specific name |
| `BrandName` | string | No | — | Brand name |
| `barCode` | string | No | — | Barcode |
| `CosmeticNumber` | string | No | — | Cosmetic registration number |
| `page` | int | No | 1 | Page number |
| `limit` | int | No | — | Results per page |

```php
SaudiFda::cosmetics()->search(['BrandName' => 'AVON', 'page' => 1]);
SaudiFda::cosmetics()->search(['SpecificNameAr' => 'كريم', 'limit' => 10]);
SaudiFda::cosmetics()->search(['barCode' => '6281007990215']);
```

**SFDA Endpoint:** `GET /v2/cosmetics/search`

---

#### 6. `searchByKeyword(string $keyword, int $page = 1)`

Search cosmetic products by a free-text keyword with pagination.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `keyword` | string | Yes | — | Search term (goes in URL path) |
| `page` | int | No | 1 | Page number |

```php
SaudiFda::cosmetics()->searchByKeyword('AVON', 1);
SaudiFda::cosmetics()->searchByKeyword('cream', 2);
```

**SFDA Endpoint:** `GET /v2/cosmetics/search/{keyword}/{page}`

---

#### 7. `getImage(string $imageCode)`

Get product image data.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `imageCode` | string | Yes | — | Image name/code |

```php
SaudiFda::cosmetics()->getImage('IMG-2023-12345');
```

**SFDA Endpoint:** `GET /v2/cosmetics/image/{image_code}`

---

### Drugs API

**Base URL:** `https://apis.sfda.gov.sa:9002/v2/DMS`

#### 1. `list(array $options = [])`

Paginated list of registered drug products in the Saudi market.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `page` | int | No | 1 | Page number |
| `limit` | int | No | — | Results per page |

```php
SaudiFda::drugs()->list(['page' => 1, 'limit' => 100]);
SaudiFda::drugs()->list(['page' => 5]);
```

**SFDA Endpoint:** `GET /v2/DMS/drug/list?page=&limit=`

**Sample Response:**
```json
{
    "data": [
        {
            "registerNumber": "21-37-10",
            "tradeName": "ORELOX 100MG TABLETS",
            "scientificName": "CEFPODOXIME",
            "atcCode1": "J01DD14",
            "strength": "100",
            "price": "30.80",
            "pharmaceuticalForm": { "nameEn": "Tablet" },
            "marketingStatus": { "nameEn": "Marketed" },
            "legalStatus": { "nameEn": "Prescription" },
            "company": { "nameEn": "SANOFI WINTHROP INDUSTRIE" }
        }
    ],
    "currentPage": 1,
    "pageCount": 791,
    "pageSize": 15,
    "rowCount": 11856
}
```

---

### Food API

**Base URL:** `https://apis.sfda.gov.sa:9002/v2/Food`

#### 1. `list(array $options = [])`

Paginated list of food products.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `page` | int | No | 1 | Page number |
| `limit` | int | No | — | Results per page |

```php
SaudiFda::food()->list(['page' => 1, 'limit' => 50]);
```

**SFDA Endpoint:** `GET /v2/Food/product/list/{page}?limit=`

---

#### 2. `getById(int $productId)`

Get a food product by its SFDA ID.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `productId` | int | Yes | — | SFDA product identifier |

```php
SaudiFda::food()->getById(1449070);
```

**SFDA Endpoint:** `GET /v2/Food/product/id/{id}`

---

#### 3. `getByReferenceNumber(string $referenceNumber)`

Get a food product by its reference number.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `referenceNumber` | string | Yes | — | Reference number (e.g., `P-3-N-200621-107719`) |

```php
SaudiFda::food()->getByReferenceNumber('P-3-N-200621-107719');
```

**SFDA Endpoint:** `GET /v2/Food/product/referencenumber/{referenceNumber}`

---

#### 4. `getByBarcode(string $barcode)`

Get a food product by its barcode.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `barcode` | string | Yes | — | Product barcode |

```php
SaudiFda::food()->getByBarcode('50254156');
```

**SFDA Endpoint:** `GET /v2/Food/product/barcode/{barcode}`

---

#### 5. `search(array $options = [])`

Search food products by keyword.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `keyword` | string | Yes | — | Search term |
| `page` | int | No | 1 | Page number |

```php
SaudiFda::food()->search(['keyword' => 'chocolate', 'page' => 1]);
SaudiFda::food()->search(['keyword' => 'milk', 'page' => 2]);
```

**SFDA Endpoint:** `GET /v2/Food/product/search/{keyword}/{page}`

---

#### 6. `getImage(string $imageCode)`

Get food product image data.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `imageCode` | string | Yes | — | Image name/code |

```php
SaudiFda::food()->getImage('FOOD-IMG-12345');
```

**SFDA Endpoint:** `GET /v2/Food/image/{image_code}`

---

### Medical Devices API

**Base URL:** `https://apis.sfda.gov.sa:9002/v2/dwh-md`

The Medical Devices API is split into three categories.

#### Low Risk Devices

##### `listLowRisk(array $options = [])`

Paginated list of low-risk medical devices.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `page` | int | No | 1 | Page number |
| `limit` | int | No | — | Results per page |

```php
SaudiFda::medicalDevices()->listLowRisk(['page' => 1]);
SaudiFda::medicalDevices()->listLowRisk(['page' => 2, 'limit' => 10]);
```

**SFDA Endpoint:** `GET /v2/dwh-md/Lowrisk/list/{page}?limit=`

---

##### `getLowRiskProduct(?int $lowRiskId = null, ?int $productId = null, ?string $accountNumber = null, ?string $registrationNumber = null, ?string $crNumber = null)`

Get a low-risk device by any combination of identifiers. At least one parameter should be provided.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `lowRiskId` | int | No | null | Low Risk ID |
| `productId` | int | No | null | Product ID |
| `accountNumber` | string | No | null | Account number |
| `registrationNumber` | string | No | null | Registration/license number |
| `crNumber` | string | No | null | Commercial Registration (CR) number |

```php
SaudiFda::medicalDevices()->getLowRiskProduct(lowRiskId: 123);
SaudiFda::medicalDevices()->getLowRiskProduct(registrationNumber: 'LIC-123');
SaudiFda::medicalDevices()->getLowRiskProduct(crNumber: 'CR-456');
```

**SFDA Endpoint:** `GET /v2/dwh-md/Lowrisk/Product?LowRiskID=&productID=&AccountNumber=&RegistrationNumber=&CrNumber=`

---

##### `searchLowRisk(string $keyword, int $page = 1)`

Search low-risk devices by keyword.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `keyword` | string | Yes | — | Search term |
| `page` | int | No | 1 | Page number |

```php
SaudiFda::medicalDevices()->searchLowRisk('face mask', 1);
```

**SFDA Endpoint:** `GET /v2/dwh-md/Lowrisk/search/{keyword}/{page}`

---

#### GHTF Devices

##### `listGHTF(array $options = [])`

Paginated list of GHTF devices.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `page` | int | No | 1 | Page number |
| `limit` | int | No | — | Results per page |

```php
SaudiFda::medicalDevices()->listGHTF(['page' => 1]);
```

**SFDA Endpoint:** `GET /v2/dwh-md/GHTF/list/{page}?limit=`

---

##### `getGHTFProduct(?int $propertiesId = null, ?int $mdId = null, ?string $referenceNumber = null, ?string $accountNumber = null, ?string $deviceNumber = null, ?string $crNumber = null)`

Get a GHTF device by any combination of identifiers.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `propertiesId` | int | No | null | Properties ID |
| `mdId` | int | No | null | MD ID |
| `referenceNumber` | string | No | null | Reference number |
| `accountNumber` | string | No | null | Account number |
| `deviceNumber` | string | No | null | Device/license number |
| `crNumber` | string | No | null | Commercial Registration number |

```php
SaudiFda::medicalDevices()->getGHTFProduct(propertiesId: 456);
SaudiFda::medicalDevices()->getGHTFProduct(deviceNumber: 'LIC-456');
```

**SFDA Endpoint:** `GET /v2/dwh-md/GHTF/Product?PropertiesId=&MDId=&ReferenceNumber=&AccountNumber=&DeviceNumber=&CrNumber=`

---

##### `getGHTFAccessory(int $propertiesId)`

Get GHTF device accessory details.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `propertiesId` | int | Yes | — | Properties ID of the accessory |

```php
SaudiFda::medicalDevices()->getGHTFAccessory(11);
```

**SFDA Endpoint:** `GET /v2/dwh-md/GHTF/Accessory/id/{PropertiesId}`

---

##### `searchGHTF(string $keyword, int $page = 1)`

Search GHTF devices by keyword.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `keyword` | string | Yes | — | Search term |
| `page` | int | No | 1 | Page number |

```php
SaudiFda::medicalDevices()->searchGHTF('hospital bed', 1);
```

**SFDA Endpoint:** `GET /v2/dwh-md/GHTF/search/{keyword}/{page}`

---

#### TFA Devices

##### `listTFA(array $options = [])`

Paginated list of TFA devices.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `page` | int | No | 1 | Page number |
| `limit` | int | No | — | Results per page |

```php
SaudiFda::medicalDevices()->listTFA(['page' => 1]);
```

**SFDA Endpoint:** `GET /v2/dwh-md/TFA/list/{page}?limit=`

---

##### `getTFAAccessory(int $propertiesId)`

Get TFA device accessory details.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `propertiesId` | int | Yes | — | Properties ID of the accessory |

```php
SaudiFda::medicalDevices()->getTFAAccessory(11);
```

**SFDA Endpoint:** `GET /v2/dwh-md/TFA/Accessory/id/{PropertiesId}`

---

##### `searchTFA(string $keyword, int $page = 1)`

Search TFA devices by keyword.

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `keyword` | string | Yes | — | Search term |
| `page` | int | No | 1 | Page number |

```php
SaudiFda::medicalDevices()->searchTFA('ultrasound', 1);
```

**SFDA Endpoint:** `GET /v2/dwh-md/TFA/search/{keyword}/{page}`

---

## API Routes

The package registers built-in API routes under `/api/saudi-fda` (configurable via `SFDA_ROUTES_PREFIX`). All routes resolve the `SaudiFdaClient` via Laravel's service container.

| Method | Endpoint | Description | Route Name |
|---|---|---|---|
| `GET` | `/api/saudi-fda/status` | Package health check | `saudi-fda.status` |
| `POST` | `/api/saudi-fda/auth/token` | Get OAuth2 access token | `saudi-fda.auth.token` |
| `GET` | `/api/saudi-fda/cosmetics` | List cosmetics (query: `page`, `limit`, `Keyword`) | `saudi-fda.cosmetics.list` |
| `GET` | `/api/saudi-fda/cosmetics/{id}` | Get cosmetic by product ID | `saudi-fda.cosmetics.by-id` |
| `GET` | `/api/saudi-fda/cosmetics/number/{cosmeticNumber}` | Get cosmetic by registration number | `saudi-fda.cosmetics.by-number` |
| `GET` | `/api/saudi-fda/cosmetics/barcode/{barcode}` | Get cosmetic by barcode | `saudi-fda.cosmetics.by-barcode` |
| `POST` | `/api/saudi-fda/cosmetics/search` | Advanced cosmetics search | `saudi-fda.cosmetics.search` |
| `GET` | `/api/saudi-fda/drugs` | List drugs (query: `page`, `limit`) | `saudi-fda.drugs.list` |
| `GET` | `/api/saudi-fda/food` | List food products | `saudi-fda.food.list` |
| `GET` | `/api/saudi-fda/food/{id}` | Get food by ID | `saudi-fda.food.by-id` |
| `POST` | `/api/saudi-fda/food/search` | Search food products | `saudi-fda.food.search` |
| `GET` | `/api/saudi-fda/medical-devices/low-risk` | List Low Risk devices | `saudi-fda.medical-devices.low-risk` |
| `GET` | `/api/saudi-fda/medical-devices/ghtf` | List GHTF devices | `saudi-fda.medical-devices.ghtf` |
| `GET` | `/api/saudi-fda/medical-devices/tfa` | List TFA devices | `saudi-fda.medical-devices.tfa` |

To disable routes, set `SFDA_ROUTES_ENABLED=false` in your `.env`.

---

## Error Handling

Every API method throws one of two exception types:

| Exception | When |
|---|---|
| `AuthenticationException` | Invalid or missing credentials |
| `SaudiFdaException` | API error (network, rate limit, 4xx/5xx, timeout) |

```php
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Exceptions\AuthenticationException;

try {
    $products = SaudiFda::cosmetics()->list();
} catch (AuthenticationException $e) {
    // Check SFDA_CONSUMER_KEY and SFDA_CONSUMER_SECRET
    report($e);
} catch (SaudiFdaException $e) {
    // Network error, rate limit, or SFDA server error
    report($e);
}
```

---

## Database Logging

Every API request/response can be stored in the `sfda_api_logs` table for auditing, debugging, and analytics.

**Enable database logging in `.env`:**

```env
SFDA_LOG_DATABASE_ENABLED=true
SFDA_LOG_DATABASE_CONNECTION=mysql   # optional, defaults to default DB connection
```

**Create the table:**

```bash
php artisan vendor:publish --tag=saudi-fda-migrations
php artisan migrate
```

**What gets logged:**

| Column | Type | Description |
|---|---|---|
| `service` | string | API service name (`cosmetics`, `drugs`, `food`, `medical_devices`) |
| `endpoint` | string | API endpoint called |
| `method` | string | HTTP method (`GET`) |
| `http_code` | int | HTTP status code |
| `request_payload` | json | Request parameters (masked for sensitive data) |
| `response_payload` | json | API response data (masked for sensitive data) |
| `error_message` | text | Error message if the request failed |
| `duration_ms` | float | Request duration in milliseconds |
| `ip_address` | string | Client IP address |
| `created_at` | timestamp | When the request was made |

**Query logs with Eloquent:**

```php
use Aghfatehi\SaudiFda\Models\SaudiFdaApiLog;

// Recent failed requests
$failures = SaudiFdaApiLog::whereNotNull('error_message')
    ->latest()
    ->take(10)
    ->get();

// Slow requests (> 2 seconds)
$slow = SaudiFdaApiLog::where('duration_ms', '>', 2000)
    ->latest()
    ->get();

// Requests by service
$cosmeticsLogs = SaudiFdaApiLog::where('service', 'cosmetics')
    ->whereDate('created_at', today())
    ->get();
```

**Sensitive data masking:** When database logging is enabled, the package automatically masks credentials, tokens, and authorization headers in the logged payloads (e.g., `Ejmb****`).

---

## Events

| Event | Fired When | Payload |
|---|---|---|
| `ApiRequestSucceeded` | Any API request succeeds | Endpoint + duration |
| `ApiRequestFailed` | Any API request fails | Endpoint + response data |

---

## Artisan Commands

```bash
# Full health check — config + authentication + API connectivity
php artisan saudi-fda:check

# Test authentication only
php artisan saudi-fda:check --auth

# View current configuration (credentials masked)
php artisan saudi-fda:check --config
```

---

## Testing

```bash
vendor/bin/phpunit
```

The package includes PHPUnit tests for:
- Facade resolution
- Singleton service instances
- Configuration checks
- Authentication errors

**CI:** GitHub Actions runs tests across PHP 8.1–8.4 x Laravel 9–13 (26 matrix combinations).

---

## Postman Collection

The repository includes a complete Postman collection: **[SFDA-API-Postman.json](SFDA-API-Postman.json)**

**Features:**
- All 24 SFDA API endpoints with response examples
- Pre-request script for automatic OAuth2 token acquisition
- Test scripts that validate responses and handle 401 token expiry
- Uses environment variables for credentials (never hardcoded)

**How to use:**
1. Postman -> Import -> Select `SFDA-API-Postman.json`
2. Click **Environment** -> **Add** (or edit an existing environment)
3. Add these **Environment variables**:
   - `SFDA_CONSUMER_KEY` = your consumer key
   - `SFDA_CONSUMER_SECRET` = your consumer secret
4. Make your first request — the token is fetched automatically

---

## License

MIT — Created by [AL-AGHBARI Fatehi](https://github.com/aghfatehi) — [FsoftDev.com](https://fsoftdev.com)
