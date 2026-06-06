<!--
  SEO: الهيئة العامة للغذاء والدواء, SFDA, SFDA API, ربط SFDA مع Laravel,
  SFDA Laravel Package, SFDA cosmetics, SFDA drugs, SFDA food, SFDA medical devices,
  الغاء والدواء السعودية, API هيئة الغذاء والدواء
-->

# ربط الهيئة العامة للغذاء والدواء السعودية (SFDA) مع Laravel — SFDA Laravel Package

[![Latest Version](https://img.shields.io/packagist/v/aghfatehi/laravel-saudi-fda)](https://packagist.org/packages/aghfatehi/laravel-saudi-fda)
[![Total Downloads](https://img.shields.io/packagist/dt/aghfatehi/laravel-saudi-fda)](https://packagist.org/packages/aghfatehi/laravel-saudi-fda)
[![License](https://img.shields.io/packagist/l/aghfatehi/laravel-saudi-fda)](https://packagist.org/packages/aghfatehi/laravel-saudi-fda)
[![PHP Version](https://img.shields.io/packagist/php-v/aghfatehi/laravel-saudi-fda)](https://packagist.org/packages/aghfatehi/laravel-saudi-fda)

حزمة لربط تطبيقات Laravel مع واجهات برمجة التطبيقات (API) الخاصة بالهيئة العامة للغذاء والدواء السعودية.

تدعم:
- **OAuth2** - المصادقة الآلية
-  **مستحضرات التجميل** - البحث والاستعلام عن المنتجات
- **الأدوية** - تصفح المنتجات الصيدلانية المسجلة
- **الغذاء** - البحث عن المنتجات الغذائية
- **الأجهزة الطبية** - تصفح الأجهزة منخفضة الخطورة و GHTF و TFA

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
## الإعدادات

أضف هذه المتغيرات إلى ملف `.env`:

```env
SFDA_CONSUMER_KEY=مفتاح_المستهلك_الخاص_بك
SFDA_CONSUMER_SECRET=المفتاح_السري_الخاص_بك
SFDA_ENVIRONMENT=sandbox
```

### جدول المتغيرات

| المتغير | القيمة الافتراضية | الشرح |
|---|---|---|
| `SFDA_CONSUMER_KEY` | — | مفتاح المستهلك من هيئة الغذاء والدواء (إجباري) |
| `SFDA_CONSUMER_SECRET` | — | المفتاح السري (إجباري) |
| `SFDA_ENVIRONMENT` | `sandbox` | `sandbox` للاختبار أو `production` للإنتاج |
| `SFDA_TOKEN_CACHE_ENABLED` | `true` | حفظ التوكن مؤقتاً لتجنب الطلبات المتكررة |
| `SFDA_TOKEN_CACHE_STORE` | `file` | مكان التخزين المؤقت (file, redis, ...) |
| `SFDA_API_TIMEOUT` | `60` | المهلة الزمنية للطلبات بالثواني |
| `SFDA_ROUTES_ENABLED` | `true` | تفعيل/تعطيل المسارات المضمنة |
| `SFDA_LOG_LEVEL` | `info` | مستوى التسجيل (debug, info, warning, error) |

---

## البداية السريعة

### 1. فحص الاتصال

```bash
php artisan saudi-fda:check
```

### 2. استخدام الواجهة (Facade)

```php
use Aghfatehi\SaudiFda\Facades\SaudiFda;

// جلب قائمة مستحضرات التجميل
$cosmetics = SaudiFda::cosmetics()->list();

// البحث في المنتجات الغذائية
$food = SaudiFda::food()->search('عسل');

// تصفح الأدوية
$drugs = SaudiFda::drugs()->list(['page' => 1, 'limit' => 20]);
```

### 3. استخدام Dependency Injection

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

### 4. المسارات المضمنة

| الطريقة | المسار | الشرح |
|---|---|---|
| `GET` | `/api/saudi-fda/status` | حالة الباكيج |
| `POST` | `/api/saudi-fda/auth/token` | الحصول على توكن جديد |
| `GET` | `/api/saudi-fda/cosmetics` | قائمة التجميل |
| `GET` | `/api/saudi-fda/cosmetics/{id}` | تجميل بواسطة المعرف |
| `GET` | `/api/saudi-fda/cosmetics/barcode/{barcode}` | تجميل بواسطة الباركود |
| `POST` | `/api/saudi-fda/cosmetics/search` | بحث في التجميل |
| `GET` | `/api/saudi-fda/drugs` | قائمة الأدوية |
| `GET` | `/api/saudi-fda/food` | قائمة المنتجات الغذائية |
| `POST` | `/api/saudi-fda/food/search` | بحث في الغذاء |
| `GET` | `/api/saudi-fda/medical-devices/low-risk` | الأجهزة منخفضة الخطورة |
| `GET` | `/api/saudi-fda/medical-devices/ghtf` | أجهزة GHTF |
| `GET` | `/api/saudi-fda/medical-devices/tfa` | أجهزة TFA |

---

## تفاصيل الاستخدام

### المصادقة

المصادقة **تلقائية بالكامل**. الباكيج يحصل على التوكن ويخزنه مؤقتاً ويعيد تجديده تلقائياً.

```php
// الحصول على توكن جديد (تجاهل التخزين المؤقت)
$token = SaudiFda::auth()->getAccessToken(true);

// الحصول على التوكن المخزن (أو طلب جديد إذا منتهي الصلاحية)
$token = SaudiFda::auth()->getAccessToken();

// التحقق من صحة البيانات
$valid = SaudiFda::auth()->validateCredentials(); // bool
```

### مستحضرات التجميل

```php
$cosmetics = SaudiFda::cosmetics();

// قائمة مع فلترة اختيارية
$cosmetics->list(['page' => 1, 'limit' => 50]);

// بواسطة المعرف من هيئة الغذاء والدواء
$cosmetics->byId('12345');

// بواسطة رقم تسجيل التجميل
$cosmetics->byCosmeticNumber('123456');

// بواسطة الباركود
$cosmetics->byBarcode('6281234567890');

// بحث بكلمة مفتاحية
$cosmetics->search('كريم');

// صورة المنتج
$cosmetics->getImage('12345');
```

### الأدوية

```php
$drugs = SaudiFda::drugs();

// قائمة الأدوية المسجلة
$drugs->list();

// مع ترقيم الصفحات
$drugs->list(['page' => 1, 'limit' => 100]);
```

### الغذاء

```php
$food = SaudiFda::food();

// قائمة المنتجات الغذائية
$food->list(['page' => 1, 'limit' => 50]);

// بواسطة المعرف
$food->byId('12345');

// بواسطة الرقم المرجعي
$food->byReferenceNumber('123456');

// بواسطة الباركود
$food->byBarcode('6281234567890');

// بحث بكلمة مفتاحية
$food->search('زيت زيتون');

// صورة المنتج
$food->getImage('12345');
```

### الأجهزة الطبية

```php
$devices = SaudiFda::medicalDevices();

// الأجهزة منخفضة الخطورة (الفئة A, B)
$devices->lowRiskList(['page' => 1, 'limit' => 50]);

// أجهزة GHTF
$devices->ghtfList(['page' => 1, 'limit' => 50]);

// أجهزة TFA
$devices->tfaList(['page' => 1, 'limit' => 50]);
```

---

## معالجة الأخطاء

```php
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Exceptions\AuthenticationException;

try {
    $products = SaudiFda::cosmetics()->list();
} catch (AuthenticationException $e) {
    Log::error('فشل المصادقة مع هيئة الغذاء والدواء');
} catch (SaudiFdaException $e) {
    Log::error('خطأ في API هيئة الغذاء والدواء');
}
```

---

## مجموعة Postman

📁 **[SFDA-API-Postman.json](SFDA-API-Postman.json)**

مجموعة كاملة لـ Postman تحتوي على جميع النقاط النهائية مع:
- مصادقة تلقائية عبر pre-request script
- أمثلة استجابة لكل طلب
- متغيرات قابلة للتعديل
- معالجة انتهاء صلاحية التوكن

**طريقة الاستخدام:**
1. افتح Postman → Import → اختر ملف JSON
2. حدث `consumer_key` و `consumer_secret` في متغيرات المجموعة
3. أرسل أول طلب — التوكن يتم جلبه تلقائياً

---

## الترخيص

MIT
