## Goal
- Build `aghfatehi/laravel-saudi-fda` Laravel package for SFDA integration, following `laravel-zatca` patterns, with a professional Postman collection.

## Constraints & Preferences
- Package name: `aghfatehi/laravel-saudi-fda`, namespace: `Aghfatehi\SaudiFda`
- Must follow `laravel-zatca` patterns (singletons, DTOs, Enums, Events, Exceptions, Logging, Facade, ServiceProvider, auto-discovery, Artisan commands, API routes)
- Support all 4 SFDA APIs + OAuth2 (client_credentials with Basic auth)
- All API methods that accept pagination/filters use a single `array $options = []` parameter (NOT scalar `int $page`) — users pass `['page' => 1, 'limit' => 50]`
- Methods with a single required value use direct params (e.g., `getById(int $id)`, `searchByKeyword(string $keyword, int $page = 1)`)
- CI: GitHub Actions matrix testing PHP 8.1–8.4 × Laravel 9–13
- No emojis, Arabic text in README.md (Arabic only in README-ar.md)
- Postman collection uses environment variables `SFDA_CONSUMER_KEY` / `SFDA_CONSUMER_SECRET`, not hardcoded credentials
- All search methods URL-encode the keyword with `urlencode()`

## Progress
### Done
- Removed "Unofficial" wording from all files
- Created `.github/workflows/laravel.yml` (26 matrix combos) + `php.yml` (syntax check)
- Added security advisory ignores to `composer.json`
- Added author `AL-AGHBARI Fatehi` prominently with homepage `https://fsoftdev.com`
- Wrote full bilingual README (`README.md` + `README-ar.md`) with all 24 endpoints documented
- Removed all Arabic text from `README.md`, kept only in `README-ar.md`
- Removed emojis from `SFDA-API-Postman.json`, `README.md`, `README-ar.md`
- Converted Postman credentials from collection variables to environment variables (`SFDA_CONSUMER_KEY`, `SFDA_CONSUMER_SECRET`)
- Added response examples to all endpoints in the Postman collection
- Renamed Postman collection to English: "SFDA - Saudi Food and Drug Authority API Collection"
- Removed Arabic from all descriptions in Postman
- Refactored all `list()` methods to accept `array $options = []` (CosmeticsService, DrugService, FoodService, MedicalDeviceService) with support for `page`, `limit`, and keyword params
- Refactored `CosmeticsService::search()` and `FoodService::search()` to accept `array $options = []`
- Added `urlencode()` to all search keyword URL path parameters (CosmeticsService::searchByKeyword, FoodService::search, MedicalDeviceService::searchLowRisk/searchGHTF/searchTFA)
- Fixed routes/api.php — corrected method names (`getById`, `getByCosmeticNumber`, `getByBarcode`, `listLowRisk`, `listGHTF`, `listTFA`) and changed search routes to pass `request()->all()`
- Rewrote README.md with comprehensive parameter documentation (type, required/optional, default, description) for every endpoint

## Key Decisions
- Service methods with configurable options use `array $options = []` — the README documents all accepted keys with their types and defaults
- Methods requiring a single key value keep direct params (e.g., `getById(int $id)`, `searchByKeyword(string $keyword, int $page = 1)`)
- URL encoding (`urlencode()`) applied to all keyword params in URL paths to handle spaces/special chars
- Routes pass `request()->all()` to array-based methods so query/body param names match array keys
- Arabic content restricted to `README-ar.md` only
- Emojis removed from all project files

## Relevant Files
- `src/Services/CosmeticsService.php` — `list(array $options)`, `search(array $options)`, `searchByKeyword(string $keyword, int $page = 1)`, `getById(int)`, `getByCosmeticNumber(string)`, `getByBarcode(string)`, `getImage(string)`
- `src/Services/DrugService.php` — `list(array $options)`
- `src/Services/FoodService.php` — `list(array $options)`, `search(array $options)`, `getById(int)`, `getByReferenceNumber(string)`, `getByBarcode(string)`, `getImage(string)`
- `src/Services/MedicalDeviceService.php` — `listLowRisk(array)`, `listGHTF(array)`, `listTFA(array)`, `getLowRiskProduct(...)`, `getGHTFProduct(...)`, `getGHTFAccessory(int)`, `getTFAAccessory(int)`, `searchLowRisk(string, int)`, `searchGHTF(string, int)`, `searchTFA(string, int)`
- `routes/api.php` — routes call correct method names and pass `request()->all()`
- `README.md` — comprehensive per-endpoint parameter docs (type, required/optional, default, description)
- `SFDA-API-Postman.json` — all 24 endpoints with response examples, env var credentials
- `.github/workflows/laravel.yml` — 26 matrix combos
- `.github/workflows/php.yml` — syntax check + composer validate
