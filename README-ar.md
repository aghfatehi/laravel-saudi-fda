<!--
  المؤلف: AL-AGHBARI Fatehi (الغباري فتحي)
  الشركة: FsoftDev.com
  الموقع: https://fsoftdev.com | https://github.com/aghfatehi
  الحزمة: aghfatehi/laravel-saudi-fda
  SEO: ربط الهيئة العامة للغذاء والدواء مع لارافيل, SFDA Laravel, SFDA API
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

<h1 align="center">ربط الهيئة العامة للغذاء والدواء السعودية (SFDA) مع Laravel</h1>
<h3 align="center">دمج واجهات هيئة الغذاء والدواء العامة مع لارافيل — مستحضرات التجميل، الأدوية، المنتجات الغذائية، الأجهزة الطبية</h3>
<h3 align="center">Laravel package for the Saudi Food and Drug Authority public APIs — Cosmetics, Drugs, Food, Medical Devices</h3>
<h4 align="center">من <a href="https://fsoftdev.com">FsoftDev.com</a> &mdash; <a href="https://github.com/aghfatehi">AL-AGHBARI Fatehi (الغباري فتحي)</a></h4>

<p align="center">
    <strong>حزمة لربط هيئة الغذاء والدواء السعودية مع لارافيل: مصادقة تلقائية OAuth2، واجهات التجميل والأدوية والغذاء والأجهزة الطبية. SFDA integration for Laravel — automatic OAuth2 authentication, cosmetics & drug & food & medical device APIs</strong>
</p>

---

## المتطلبات

| المتطلب | الإصدار |
|---|---|
| **PHP** | `^8.1` |
| **Laravel** | `9.x` · `10.x` · `11.x` · `12.x` · `13.x` |
| **الإضافات** | `json`, `curl` |

## التركيب

```bash
composer require aghfatehi/laravel-saudi-fda
```

```bash
# نشر الإعدادات (اختياري)
php artisan vendor:publish --tag=saudi-fda-config
```

---

## الإعدادات

أضف هذه المتغيرات إلى ملف `.env`:

```env
SFDA_CONSUMER_KEY=مفتاح_المستهلك
SFDA_CONSUMER_SECRET=المفتاح_السري
SFDA_ENVIRONMENT=sandbox
```

| المتغير | القيمة الافتراضية | الشرح |
|---|---|---|
| `SFDA_CONSUMER_KEY` | — | مفتاح المستهلك **(إجباري)** |
| `SFDA_CONSUMER_SECRET` | — | المفتاح السري **(إجباري)** |
| `SFDA_ENVIRONMENT` | `sandbox` | `sandbox` للاختبار أو `production` للإنتاج |
| `SFDA_TOKEN_CACHE_ENABLED` | `true` | حفظ التوكن مؤقتاً |
| `SFDA_TOKEN_CACHE_STORE` | `file` | مكان التخزين المؤقت |
| `SFDA_API_TIMEOUT` | `60` | المهلة الزمنية بالثواني |
| `SFDA_ROUTES_ENABLED` | `true` | تفعيل المسارات المضمنة |
| `SFDA_LOG_LEVEL` | `info` | مستوى التسجيل |

---

## البداية السريعة

```bash
php artisan saudi-fda:check
```

```php
use Aghfatehi\SaudiFda\Facades\SaudiFda;

// التحقق من الإعدادات
SaudiFda::isConfigured(); // bool

// التحقق من جاهزية API
SaudiFda::isReady(); // bool

// معرفة البيئة
SaudiFda::environment();
```

---

## المصادقة (OAuth2)

المصادقة **تلقائية بالكامل**. التوكن يُجلب ويُخزّن ويُجدّد دون تدخل منك.

**نقطة النهاية:** `POST https://apis.sfda.gov.sa:9002/v2/oauth/accesstoken?grant_type=client_credentials`
**طريقة المصادقة:** Basic (Consumer Key : Consumer Secret)
**صلاحية التوكن:** 24 ساعة (86400 ثانية)

```php
// الحصول على توكن جديد (تجاهل التخزين المؤقت)
$token = SaudiFda::auth()->getAccessToken(true);

// الحصول على التوكن المخزن (أو طلب جديد إذا منتهي)
$token = SaudiFda::auth()->getAccessToken();

// التحقق من صحة البيانات
SaudiFda::auth()->validateCredentials(); // bool
```

> **كيف يعمل التجديد التلقائي:** عند استلام رد 401، يقوم الباكيج تلقائياً بطلب توكن جديد وإعادة المحاولة. وقبل كل طلب API، يتحقق الباكيج من وجود توكن صالح ويطلبه إذا كان مفقوداً.

---

## مستحضرات التجميل (Cosmetics)

**الرابط الأساسي:** `https://apis.sfda.gov.sa:9002/v2/cosmetics`

```php
$cosmetics = SaudiFda::cosmetics();

// 1. قائمة مع ترقيم الصفحات
$cosmetics->list(['page' => 1, 'limit' => 50]);
// SFDA: GET /v2/cosmetics/list?page=1&Keyword=

// 2. بواسطة المعرف
$cosmetics->byId(1495);
// SFDA: GET /v2/cosmetics/Product_Id/{id}

// 3. بواسطة رقم تسجيل التجميل
$cosmetics->byCosmeticNumber('CN-2023-08203');
// SFDA: GET /v2/cosmetics/cosmeticNumber/{number}

// 4. بواسطة الباركود
$cosmetics->byBarcode('6281007990215');
// SFDA: GET /v2/cosmetics/BarCode/{barcode}

// 5. بحث متقدم
$cosmetics->search(['BrandName' => 'AVON', 'page' => 1]);
// SFDA: GET /v2/cosmetics/search?BrandName=AVON&...

// 6. بحث بكلمة مفتاحية
$cosmetics->searchByKeyword('AVON', 1);
// SFDA: GET /v2/cosmetics/search/{keyword}/{page}

// 7. صورة المنتج
$cosmetics->getImage('IMG-2023-12345');
// SFDA: GET /v2/cosmetics/image/{image_code}
```

---

## الأدوية (Drugs)

**الرابط الأساسي:** `https://apis.sfda.gov.sa:9002/v2/DMS`

```php
$drugs = SaudiFda::drugs();

// قائمة الأدوية المسجلة
$drugs->list(['page' => 1, 'limit' => 100]);
// SFDA: GET /v2/DMS/drug/list?page=1
```

**مثال على الاستجابة:**
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

## المنتجات الغذائية (Food)

**الرابط الأساسي:** `https://apis.sfda.gov.sa:9002/v2/Food`

```php
$food = SaudiFda::food();

// 1. قائمة مع ترقيم الصفحات
$food->list(['page' => 1, 'limit' => 50]);
// SFDA: GET /v2/Food/product/list/{page}

// 2. بواسطة المعرف
$food->byId(1449070);
// SFDA: GET /v2/Food/product/id/{id}

// 3. بواسطة الرقم المرجعي
$food->byReferenceNumber('P-3-N-200621-107719');
// SFDA: GET /v2/Food/product/referencenumber/{ref}

// 4. بواسطة الباركود
$food->byBarcode('50254156');
// SFDA: GET /v2/Food/product/barcode/{barcode}

// 5. بحث بكلمة مفتاحية
$food->search('شوكولاتة', 1);
// SFDA: GET /v2/Food/product/search/{keyword}/{page}

// 6. صورة المنتج
$food->getImage('FOOD-IMG-12345');
// SFDA: GET /v2/Food/image/{image_code}
```

---

## الأجهزة الطبية (Medical Devices)

**الرابط الأساسي:** `https://apis.sfda.gov.sa:9002/v2/dwh-md`

### الأجهزة منخفضة الخطورة (Low Risk)

```php
$devices = SaudiFda::medicalDevices();

// القائمة
$devices->lowRiskList(['page' => 1]);
// SFDA: GET /v2/dwh-md/Lowrisk/list/{page}

// بواسطة المعرف
$devices->lowRiskById(123);
// SFDA: GET /v2/dwh-md/Lowrisk/Product?LowRiskID=123

// بحث
$devices->lowRiskSearch('face mask', 1);
// SFDA: GET /v2/dwh-md/Lowrisk/search/{keyword}/{page}
```

### أجهزة GHTF

```php
// القائمة
$devices->ghtfList(['page' => 1]);
// SFDA: GET /v2/dwh-md/GHTF/list/{page}

// بواسطة المعرف
$devices->ghtfById(456);
// SFDA: GET /v2/dwh-md/GHTF/Product?PropertiesId=456

// الملحقات
$devices->ghtfAccessory(11);
// SFDA: GET /v2/dwh-md/GHTF/Accessory/id/{id}

// بحث
$devices->ghtfSearch('hospital bed', 1);
// SFDA: GET /v2/dwh-md/GHTF/search/{keyword}/{page}
```

### أجهزة TFA

```php
// القائمة
$devices->tfaList(['page' => 1]);
// SFDA: GET /v2/dwh-md/TFA/list/{page}

// الملحقات
$devices->tfaAccessory(11);
// SFDA: GET /v2/dwh-md/TFA/Accessory/id/{id}

// بحث
$devices->tfaSearch('ultrasound', 1);
// SFDA: GET /v2/dwh-md/TFA/search/{keyword}/{page}
```

---

## المسارات المضمنة

| الطريقة | المسار | الشرح |
|---|---|---|
| `GET` | `/api/saudi-fda/status` | فحص حالة الباكيج |
| `POST` | `/api/saudi-fda/auth/token` | الحصول على توكن جديد |
| `GET` | `/api/saudi-fda/cosmetics` | قائمة مستحضرات التجميل |
| `GET` | `/api/saudi-fda/cosmetics/{id}` | تجميل بواسطة المعرف |
| `GET` | `/api/saudi-fda/cosmetics/number/{number}` | تجميل بواسطة رقم التسجيل |
| `GET` | `/api/saudi-fda/cosmetics/barcode/{barcode}` | تجميل بواسطة الباركود |
| `POST` | `/api/saudi-fda/cosmetics/search` | بحث في التجميل |
| `GET` | `/api/saudi-fda/drugs` | قائمة الأدوية |
| `GET` | `/api/saudi-fda/food` | قائمة المنتجات الغذائية |
| `GET` | `/api/saudi-fda/food/{id}` | غذاء بواسطة المعرف |
| `POST` | `/api/saudi-fda/food/search` | بحث في الغذاء |
| `GET` | `/api/saudi-fda/medical-devices/low-risk` | الأجهزة منخفضة الخطورة |
| `GET` | `/api/saudi-fda/medical-devices/ghtf` | أجهزة GHTF |
| `GET` | `/api/saudi-fda/medical-devices/tfa` | أجهزة TFA |

---

## معالجة الأخطاء

```php
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Exceptions\AuthenticationException;

try {
    $products = SaudiFda::cosmetics()->list();
} catch (AuthenticationException $e) {
    Log::error('فشل المصادقة مع هيئة الغذاء والدواء: ' . $e->getMessage());
} catch (SaudiFdaException $e) {
    Log::error('خطأ في API: ' . $e->getMessage());
}
```

---

## الأحداث (Events)

| الحدث | وقت الإطلاق | الحمولة |
|---|---|---|
| `ApiRequestSucceeded` | نجاح طلب API | النقطة + المدة |
| `ApiRequestFailed` | فشل طلب API | النقطة + بيانات الاستجابة |

---

## أوامر Artisan

```bash
# فحص كامل
php artisan saudi-fda:check

# اختبار المصادقة فقط
php artisan saudi-fda:check --auth

# عرض الإعدادات
php artisan saudi-fda:check --config
```

---

## مجموعة Postman

📁 **[SFDA-API-Postman.json](SFDA-API-Postman.json)**

تحتوي على جميع نقاط النهاية الـ 24 مع:
- مصادقة تلقائية عبر pre-request script
- أمثلة استجابة لكل طلب
- متغيرات قابلة للتعديل

**الاستيراد:** Postman → Import → اختر `SFDA-API-Postman.json`

---

## الترخيص

MIT — من إنشاء [AL-AGHBARI Fatehi (الغباري فتحي)](https://github.com/aghfatehi) — [FsoftDev.com](https://fsoftdev.com)

**كلمات مفتاحية:** هيئة الغذاء والدواء السعودية, SFDA, SFDA API Laravel, الهيئة العامة للغذاء والدواء, ربط SFDA مع Laravel, SFDA cosmetics, SFDA drugs, SFDA food, SFDA medical devices, API هيئة الغذاء والدواء, الغباري فتحي, AL-AGHBARI Fatehi, لارافيل 9, لارافيل 10, لارافيل 11, لارافيل 12, لارافيل 13
