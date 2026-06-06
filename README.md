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
<h4 align="center">By <a href="https://fsoftdev.com">FsoftDev.com</a> &mdash; <a href="https://github.com/aghfatehi">AL-AGHBARI Fatehi</a></h4>

<p align="center">
    <strong>SFDA integration for Laravel — automatic OAuth2 authentication, cosmetics & drug & food & medical device APIs</strong>
</p>

---

## Table of Contents

- [Requirements & Installation](#requirements--installation)
- [Configuration](#configuration)
- [Quick Start](#quick-start)
- [Authentication](#authentication)
- [Cosmetics API](#cosmetics-api)
- [Drugs API](#drugs-api)
- [Food API](#food-api)
- [Medical Devices API](#medical-devices-api)
- [Built-in API Routes](#built-in-api-routes)
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

Laravel auto-discovers the service provider. No manual registration needed.

```bash
# Publish config (optional)
php artisan vendor:publish --tag=saudi-fda-config
```

---

## Configuration

Add to `.env`:

```env
SFDA_CONSUMER_KEY=your_consumer_key
SFDA_CONSUMER_SECRET=your_consumer_secret
SFDA_ENVIRONMENT=sandbox
```

| Variable | Default | Description |
|---|---|---|
| `SFDA_CONSUMER_KEY` | — | Your SFDA Consumer Key **(required)** |
| `SFDA_CONSUMER_SECRET` | — | Your SFDA Consumer Secret **(required)** |
| `SFDA_ENVIRONMENT` | `sandbox` | `sandbox` or `production` |
| `SFDA_TOKEN_CACHE_ENABLED` | `true` | Cache access token |
| `SFDA_TOKEN_CACHE_STORE` | `file` | Cache store (file, redis, etc.) |
| `SFDA_TOKEN_CACHE_KEY` | `sfda_access_token` | Cache key for token |
| `SFDA_API_TIMEOUT` | `60` | Request timeout (seconds) |
| `SFDA_ROUTES_ENABLED` | `true` | Enable built-in routes |
| `SFDA_ROUTES_PREFIX` | `api/saudi-fda` | Routes prefix |
| `SFDA_LOGGING_ENABLED` | `true` | Enable request logging |
| `SFDA_LOG_LEVEL` | `info` | Log level |

Base URLs can also be overridden per service via `SFDA_OAUTH_BASE`, `SFDA_COSMETICS_BASE`, `SFDA_DRUGS_BASE`, `SFDA_FOOD_BASE`, `SFDA_MEDICAL_DEVICES_BASE`.

---

## Quick Start

```bash
# Health check
php artisan saudi-fda:check

# View configuration
php artisan saudi-fda:check --config
```

### Using the Facade

```php
use Aghfatehi\SaudiFda\Facades\SaudiFda;

// Check if credentials are configured
SaudiFda::isConfigured(); // bool

// Check if API is ready (valid token obtained)
SaudiFda::isReady(); // bool

// Get current environment
SaudiFda::environment(); // \Aghfatehi\SaudiFda\Enums\Environment
```

### Dependency Injection

```php
use Aghfatehi\SaudiFda\SaudiFdaClient;

class ProductController extends Controller
{
    public function __construct(private SaudiFdaClient $sfda) {}

    public function search(Request $request)
    {
        return $this->sfda->cosmetics()->byBarcode($request->barcode);
    }
}
```

---

## Authentication

The **OAuth2 Client Credentials** flow is fully automatic. The package obtains, caches, and refreshes the access token transparently.

**API Endpoint:** `POST https://apis.sfda.gov.sa:9002/v2/oauth/accesstoken?grant_type=client_credentials`
**Auth Method:** HTTP Basic (Consumer Key : Consumer Secret)
**Token Expiry:** 24 hours (86400 seconds)

```php
// Force a new token (bypass cache)
$token = SaudiFda::auth()->getAccessToken(true);

// Get cached or new token
$token = SaudiFda::auth()->getAccessToken();

// $token is an AccessTokenDTO with:
$token->accessToken; // string - the Bearer token
$token->expiresIn;   // int - seconds until expiry

// Check credentials validity
SaudiFda::auth()->validateCredentials(); // bool
```

> **How auto-refresh works:** If any API call receives a 401 response, the package automatically requests a new token and retries the request once. Additionally, before every request, the package checks whether a valid token exists and obtains one if missing.

---

## Cosmetics API

**Base URL:** `https://apis.sfda.gov.sa:9002/v2/cosmetics`

The Cosmetics API provides 7 endpoints for querying registered cosmetic products in the Saudi market.

### 1. List Cosmetics (Paginated)

```php
$products = SaudiFda::cosmetics()->list(['page' => 1, 'limit' => 50]);
```
**SFDA Endpoint:** `GET /v2/cosmetics/list?page=1&Keyword=`
**Query Params:** `page` (int, optional, default: 1), `Keyword` (string, optional)
**Returns:** Paginated list of cosmetic products with `currentPage`, `pageCount`, `pageSize`, `rowCount`, and `results` array.

### 2. Get by Product ID

```php
$product = SaudiFda::cosmetics()->byId(1495);
```
**SFDA Endpoint:** `GET /v2/cosmetics/Product_Id/{productID}`
**Path Params:** `productID` (int, required) — SFDA product identifier
**Returns:** Single cosmetic product with full details (brand, manufacturer, barcode, package volume, etc.)

### 3. Get by Cosmetic Number

```php
$product = SaudiFda::cosmetics()->byCosmeticNumber('CN-2023-08203');
```
**SFDA Endpoint:** `GET /v2/cosmetics/cosmeticNumber/{cosmeticNumber}`
**Path Params:** `cosmeticNumber` (string, required) — Cosmetic registration number

### 4. Get by Barcode

```php
$product = SaudiFda::cosmetics()->byBarcode('6281007990215');
```
**SFDA Endpoint:** `GET /v2/cosmetics/BarCode/{barcode}`
**Path Params:** `barcode` (string, required) — Product barcode

### 5. Advanced Search

```php
$results = SaudiFda::cosmetics()->search([
    'BrandName' => 'AVON',
    'SpecificName' => '',
    'SpecificNameAr' => '',
    'barCode' => '',
    'CosmeticNumber' => '',
    'page' => 1,
]);
```
**SFDA Endpoint:** `GET /v2/cosmetics/search`
**Query Params:** `SpecificNameAr`, `SpecificName`, `BrandName`, `barCode`, `CosmeticNumber`, `page` (all optional)

### 6. Search by Keyword

```php
$results = SaudiFda::cosmetics()->searchByKeyword('AVON', 1);
```
**SFDA Endpoint:** `GET /v2/cosmetics/search/{keyword}/{page}`
**Path Params:** `keyword` (string, required), `page` (int, required)

### 7. Get Product Image

```php
$image = SaudiFda::cosmetics()->getImage('IMG-2023-12345');
```
**SFDA Endpoint:** `GET /v2/cosmetics/image/{image_code}`
**Path Params:** `image_code` (string, required) — Image name/code

---

## Drugs API

**Base URL:** `https://apis.sfda.gov.sa:9002/v2/DMS`

### 1. List Drugs (Paginated)

```php
$drugs = SaudiFda::drugs()->list(['page' => 1, 'limit' => 100]);
```
**SFDA Endpoint:** `GET /v2/DMS/drug/list?page=1`
**Query Params:** `page` (int, optional, default: 1)

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

## Food API

**Base URL:** `https://apis.sfda.gov.sa:9002/v2/Food`

### 1. List Food Products (Paginated)

```php
$products = SaudiFda::food()->list(['page' => 1, 'limit' => 50]);
```
**SFDA Endpoint:** `GET /v2/Food/product/list/{page}`
**Path Params:** `page` (int, required)

### 2. Get by ID

```php
$product = SaudiFda::food()->byId(1449070);
```
**SFDA Endpoint:** `GET /v2/Food/product/id/{id}`
**Path Params:** `id` (int, required)

### 3. Get by Reference Number

```php
$product = SaudiFda::food()->byReferenceNumber('P-3-N-200621-107719');
```
**SFDA Endpoint:** `GET /v2/Food/product/referencenumber/{referenceNumber}`
**Path Params:** `referenceNumber` (string, required)

### 4. Get by Barcode

```php
$product = SaudiFda::food()->byBarcode('50254156');
```
**SFDA Endpoint:** `GET /v2/Food/product/barcode/{barcode}`
**Path Params:** `barcode` (string, required)

### 5. Search by Keyword

```php
$results = SaudiFda::food()->search('chocolate', 1);
```
**SFDA Endpoint:** `GET /v2/Food/product/search/{keyword}/{page}`
**Path Params:** `keyword` (string, required), `page` (int, required)

### 6. Get Product Image

```php
$image = SaudiFda::food()->getImage('FOOD-IMG-12345');
```
**SFDA Endpoint:** `GET /v2/Food/image/{image_code}`
**Path Params:** `image_code` (string, required)

---

## Medical Devices API

**Base URL:** `https://apis.sfda.gov.sa:9002/v2/dwh-md`

The Medical Devices API is organized into 3 categories: **Low Risk**, **GHTF** (Global Harmonization Task Force), and **TFA** (Traditional Foreign Approval).

### Low Risk Devices (3 endpoints)

```php
$devices = SaudiFda::medicalDevices();

// List (paginated)
$devices->lowRiskList(['page' => 1]);
// SFDA: GET /v2/dwh-md/Lowrisk/list/{page}

// Get by filters (LowRiskID, productID, AccountNumber, RegistrationNumber, CrNumber)
$devices->lowRiskById(123);
// SFDA: GET /v2/dwh-md/Lowrisk/Product?LowRiskID=123

// Get by license number
$devices->lowRiskByLicenseNumber('LIC-123');
// SFDA: GET /v2/dwh-md/Lowrisk/Product?RegistrationNumber=LIC-123

// Search by keyword
$devices->lowRiskSearch('face mask', 1);
// SFDA: GET /v2/dwh-md/Lowrisk/search/{keyword}/{page}
```

### GHTF Devices (4 endpoints)

```php
// List (paginated)
$devices->ghtfList(['page' => 1]);
// SFDA: GET /v2/dwh-md/GHTF/list/{page}

// Get by filters (PropertiesId, MDId, ReferenceNumber, AccountNumber, DeviceNumber, CrNumber)
$devices->ghtfById(456);
// SFDA: GET /v2/dwh-md/GHTF/Product?PropertiesId=456

// Get by license number
$devices->ghtfByLicenseNumber('LIC-456');
// SFDA: GET /v2/dwh-md/GHTF/Product?DeviceNumber=LIC-456

// Get accessory
$devices->ghtfAccessory(11);
// SFDA: GET /v2/dwh-md/GHTF/Accessory/id/{PropertiesId}

// Search by keyword
$devices->ghtfSearch('hospital bed', 1);
// SFDA: GET /v2/dwh-md/GHTF/search/{keyword}/{page}
```

### TFA Devices (3 endpoints)

```php
// List (paginated)
$devices->tfaList(['page' => 1]);
// SFDA: GET /v2/dwh-md/TFA/list/{page}

// Get by ID
$devices->tfaById(789);
// SFDA: GET /v2/dwh-md/TFA/Product?PropertiesId=789

// Get by license number
$devices->tfaByLicenseNumber('LIC-789');
// SFDA: GET /v2/dwh-md/TFA/Product?DeviceNumber=LIC-789

// Get accessory
$devices->tfaAccessory(11);
// SFDA: GET /v2/dwh-md/TFA/Accessory/id/{PropertiesId}

// Search by keyword
$devices->tfaSearch('ultrasound', 1);
// SFDA: GET /v2/dwh-md/TFA/search/{keyword}/{page}
```

---

## Built-in API Routes

The package registers these routes under `/api/saudi-fda` (configurable):

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/saudi-fda/status` | Package health status |
| `POST` | `/api/saudi-fda/auth/token` | Get fresh access token |
| `GET` | `/api/saudi-fda/cosmetics` | List cosmetics (query: `page`, `Keyword`) |
| `GET` | `/api/saudi-fda/cosmetics/{id}` | Get cosmetic by Product ID |
| `GET` | `/api/saudi-fda/cosmetics/number/{cosmeticNumber}` | Get by registration number |
| `GET` | `/api/saudi-fda/cosmetics/barcode/{barcode}` | Get by barcode |
| `POST` | `/api/saudi-fda/cosmetics/search` | Search cosmetics (body: `keyword`) |
| `GET` | `/api/saudi-fda/drugs` | List drugs (query: `page`) |
| `GET` | `/api/saudi-fda/food` | List food products |
| `GET` | `/api/saudi-fda/food/{id}` | Get food by ID |
| `POST` | `/api/saudi-fda/food/search` | Search food (body: `keyword`) |
| `GET` | `/api/saudi-fda/medical-devices/low-risk` | List Low Risk devices |
| `GET` | `/api/saudi-fda/medical-devices/ghtf` | List GHTF devices |
| `GET` | `/api/saudi-fda/medical-devices/tfa` | List TFA devices |

---

## Error Handling

```php
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Exceptions\AuthenticationException;

try {
    $products = SaudiFda::cosmetics()->list();
} catch (AuthenticationException $e) {
    // Missing or invalid credentials
    report($e);
} catch (SaudiFdaException $e) {
    // API error (network, rate limit, server error)
    report($e);
}
```

---

## Events

| Event | Fired When | Payload |
|---|---|---|
| `ApiRequestSucceeded` | Any API request succeeds | Endpoint + duration |
| `ApiRequestFailed` | Any API request fails | Endpoint + response data |

---

## Artisan Commands

```bash
# Full health check — verifies config + authentication + API connectivity
php artisan saudi-fda:check

# Test authentication only
php artisan saudi-fda:check --auth

# View current configuration (masked credentials)
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

**CI:** GitHub Actions runs tests across PHP 8.1–8.4 × Laravel 9–13 (26 matrix combinations).

---

## Postman Collection

**[SFDA-API-Postman.json](SFDA-API-Postman.json)**

Complete Postman collection with all 24 SFDA API endpoints:
- Pre-request script auto-authenticates before every request
- Test scripts validate responses and handle 401 expiry
- Collection variables for credentials and token
- Response examples for every endpoint

**Import:** Postman → Import → Select `SFDA-API-Postman.json`

---

## License

MIT — Created by [AL-AGHBARI Fatehi](https://github.com/aghfatehi) — [FsoftDev.com](https://fsoftdev.com)
