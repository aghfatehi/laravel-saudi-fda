<!--
  SEO: Saudi FDA, SFDA, SFDA API, SFDA Laravel, الهيئة العامة للغذاء والدواء, SFDA cosmetics API,
  SFDA drugs API, SFDA food API, SFDA medical devices API, laravel saudi fda, SFDA integration
-->

# Saudi FDA API Integration for Laravel — SFDA Laravel Package

[![Latest Version](https://img.shields.io/packagist/v/aghfatehi/laravel-saudi-fda)](https://packagist.org/packages/aghfatehi/laravel-saudi-fda)
[![Total Downloads](https://img.shields.io/packagist/dt/aghfatehi/laravel-saudi-fda)](https://packagist.org/packages/aghfatehi/laravel-saudi-fda)
[![License](https://img.shields.io/packagist/l/aghfatehi/laravel-saudi-fda)](https://packagist.org/packages/aghfatehi/laravel-saudi-fda)
[![PHP Version](https://img.shields.io/packagist/php-v/aghfatehi/laravel-saudi-fda)](https://packagist.org/packages/aghfatehi/laravel-saudi-fda)
[![Laravel Version](https://img.shields.io/badge/Laravel-9.x%20–%2013.x-blue)](https://packagist.org/packages/aghfatehi/laravel-saudi-fda)

Laravel package for the Saudi Food and Drug Authority (SFDA) APIs.

Supports:
- **OAuth2** authentication (client_credentials grant)
- **Cosmetics** - search and retrieve cosmetic product data
- **Drugs** - browse registered pharmaceutical products
- **Food** - search and retrieve food product data
- **Medical Devices** - browse low-risk, GHTF, and TFA devices

> [Arabic documentation (التوثيق العربي)](README-ar.md)

---

## Requirements

| Requirement | Version |
|---|---|
| **PHP** | `^8.1` |
| **Laravel** | `9.x` · `10.x` · `11.x` · `12.x` · `13.x` |
| **Extensions** | `json`, `curl` |

## Installation

```bash
composer require aghfatehi/laravel-saudi-fda
```

Laravel will auto-discover the service provider. No manual registration needed.

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=saudi-fda-config
```

---

## Configuration

Add these variables to your `.env` file:

```env
SFDA_CONSUMER_KEY=your_consumer_key_here
SFDA_CONSUMER_SECRET=your_consumer_secret_here
SFDA_ENVIRONMENT=sandbox
```

For sandbox testing, you can use the credentials configured in your application.

### Available Environment Variables

| Variable | Default | Description |
|---|---|---|
| `SFDA_CONSUMER_KEY` | — | Your SFDA Consumer Key (required) |
| `SFDA_CONSUMER_SECRET` | — | Your SFDA Consumer Secret (required) |
| `SFDA_ENVIRONMENT` | `sandbox` | `sandbox` or `production` |
| `SFDA_TOKEN_CACHE_ENABLED` | `true` | Cache the access token |
| `SFDA_TOKEN_CACHE_STORE` | `file` | Cache store (file, redis, etc.) |
| `SFDA_API_TIMEOUT` | `60` | Request timeout in seconds |
| `SFDA_ROUTES_ENABLED` | `true` | Enable/disable built-in API routes |
| `SFDA_LOG_LEVEL` | `info` | Log level (debug, info, warning, error) |

---

## Quick Start

### 1. Health Check

```bash
php artisan saudi-fda:check
```

This command verifies your configuration and tests the API connection.

```
SFDA Package Health Check

  Package Version ........... 1.0.0
  Environment ............... sandbox
  Credentials Configured ... YES
  Testing Authentication .... DONE
  Token ..................... sk0x2...abc12
  Expires In ................ 86400 seconds
```

### 2. Use the Facade

```php
use Aghfatehi\SaudiFda\Facades\SaudiFda;

// List cosmetics
$cosmetics = SaudiFda::cosmetics()->list();

// Search food products
$food = SaudiFda::food()->search('honey');

// Browse drugs
$drugs = SaudiFda::drugs()->list(['page' => 1, 'limit' => 20]);
```

### 3. Use Dependency Injection

```php
use Aghfatehi\SaudiFda\SaudiFdaClient;

class ProductController extends Controller
{
    public function search(SaudiFdaClient $sfda, Request $request)
    {
        $products = $sfda->cosmetics()->byBarcode($request->barcode);

        return response()->json($products);
    }
}
```

### 4. Use the Built-in API Routes

The package registers these routes under `/api/saudi-fda`:

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/saudi-fda/status` | Package health status |
| `POST` | `/api/saudi-fda/auth/token` | Get a fresh access token |
| `GET` | `/api/saudi-fda/cosmetics` | List cosmetics |
| `GET` | `/api/saudi-fda/cosmetics/{id}` | Cosmetics by ID |
| `GET` | `/api/saudi-fda/cosmetics/number/{number}` | Cosmetics by registration number |
| `GET` | `/api/saudi-fda/cosmetics/barcode/{barcode}` | Cosmetics by barcode |
| `POST` | `/api/saudi-fda/cosmetics/search` | Search cosmetics |
| `GET` | `/api/saudi-fda/drugs` | List drugs |
| `GET` | `/api/saudi-fda/food` | List food products |
| `GET` | `/api/saudi-fda/food/{id}` | Food by ID |
| `POST` | `/api/saudi-fda/food/search` | Search food |
| `GET` | `/api/saudi-fda/medical-devices/low-risk` | Low risk devices |
| `GET` | `/api/saudi-fda/medical-devices/ghtf` | GHTF devices |
| `GET` | `/api/saudi-fda/medical-devices/tfa` | TFA devices |

---

## Detailed Usage

### Authentication

Authentication is **automatic**. The package obtains and caches the access token for you.

```php
// Force a fresh token (bypass cache)
$token = SaudiFda::auth()->getAccessToken(true);

// Get cached token (or obtain new one if expired)
$token = SaudiFda::auth()->getAccessToken();

// Check if credentials are valid
$valid = SaudiFda::auth()->validateCredentials(); // bool
```

### Cosmetics

```php
$cosmetics = SaudiFda::cosmetics();

// List with optional filters
$cosmetics->list(['page' => 1, 'limit' => 50]);

// By SFDA ID
$cosmetics->byId('12345');

// By cosmetic registration number
$cosmetics->byCosmeticNumber('123456');

// By barcode
$cosmetics->byBarcode('6281234567890');

// Search by keyword
$cosmetics->search('cream');

// Get product image
$cosmetics->getImage('12345');
```

### Drugs

```php
$drugs = SaudiFda::drugs();

// List all registered drugs
$drugs->list();

// List with pagination
$drugs->list(['page' => 1, 'limit' => 100]);
```

### Food

```php
$food = SaudiFda::food();

// List food products
$food->list(['page' => 1, 'limit' => 50]);

// By SFDA ID
$food->byId('12345');

// By reference number
$food->byReferenceNumber('123456');

// By barcode
$food->byBarcode('6281234567890');

// Search by keyword
$food->search('olive oil');

// Get product image
$food->getImage('12345');
```

### Medical Devices

```php
$devices = SaudiFda::medicalDevices();

// Low-risk medical devices (Class A, B)
$devices->lowRiskList(['page' => 1, 'limit' => 50]);

// GHTF-classified devices
$devices->ghtfList(['page' => 1, 'limit' => 50]);

// TFA-classified devices (Traditional)
$devices->tfaList(['page' => 1, 'limit' => 50]);

// By ID
$devices->lowRiskById('12345');
$devices->ghtfById('12345');
$devices->tfaById('12345');

// By license number
$devices->lowRiskByLicenseNumber('LIC-123');
$devices->ghtfByLicenseNumber('LIC-456');
$devices->tfaByLicenseNumber('LIC-789');
```

---

## Error Handling

```php
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Exceptions\AuthenticationException;

try {
    $products = SaudiFda::cosmetics()->list();
} catch (AuthenticationException $e) {
    // Invalid or expired credentials
    Log::error('SFDA auth failed', ['message' => $e->getMessage()]);
} catch (SaudiFdaException $e) {
    // API error (rate limit, network, etc.)
    Log::error('SFDA API error', [
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
    ]);
}
```

---

## Events

The package fires these events that you can listen to:

| Event | Fired When | Payload |
|---|---|---|
| `SaudiFdaAuthenticationSucceeded` | Token obtained successfully | `AccessTokenDTO` |
| `SaudiFdaAuthenticationFailed` | Token request failed | Exception message |
| `SaudiFdaRequestFailed` | Any API request fails | Endpoint + response data |

---

## Testing

```bash
php artisan saudi-fda:check
```

---

## Postman Collection

A complete Postman collection is included:

**[SFDA-API-Postman.json](SFDA-API-Postman.json)**

Contains all 22 endpoints with:
- Auto-authentication via pre-request script
- Response examples for every request
- Reusable collection variables
- Token expiry handling

**How to use:**
1. Open Postman → Import → Select the JSON file
2. Update `consumer_key` and `consumer_secret` in the collection variables
3. Make your first request — the token is fetched automatically

---

## License

MIT
