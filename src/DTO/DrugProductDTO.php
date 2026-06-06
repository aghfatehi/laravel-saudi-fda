<?php

namespace Aghfatehi\SaudiFda\DTO;

class DrugProductDTO
{
    public function __construct(
        public readonly string $registerNumber,
        public readonly string $tradeName,
        public readonly string $tradeNameAr,
        public readonly string $scientificName,
        public readonly string $scientificNameAr,
        public readonly string $atcCode1,
        public readonly ?string $atcCode2,
        public readonly int $packageSize,
        public readonly string $strength,
        public readonly int $shelfLife,
        public readonly string $price,
        public readonly ?array $packageType = null,
        public readonly ?string $pharmaceuticalFormAr = null,
        public readonly ?string $pharmaceuticalFormEn = null,
        public readonly ?string $storageConditionAr = null,
        public readonly ?string $storageConditionEn = null,
        public readonly ?string $marketingStatusAr = null,
        public readonly ?string $marketingStatusEn = null,
        public readonly ?string $legalStatusAr = null,
        public readonly ?string $legalStatusEn = null,
        public readonly ?string $companyNameEn = null,
        public readonly ?string $companyCountryEn = null,
    ) {}

    public static function fromArray(array $data): self
    {
        $packageType = $data['packageType'] ?? null;
        $pharmaForm = $data['pharmaceuticalForm'] ?? null;
        $storage = $data['storageConditions'] ?? null;
        $marketing = $data['marketingStatus'] ?? null;
        $legal = $data['legalStatus'] ?? null;
        $company = $data['company'] ?? null;

        return new self(
            registerNumber: $data['registerNumber'] ?? '',
            tradeName: $data['tradeName'] ?? '',
            tradeNameAr: $data['tradeNameAr'] ?? '',
            scientificName: $data['scientificName'] ?? '',
            scientificNameAr: $data['scientificNameAr'] ?? '',
            atcCode1: $data['atcCode1'] ?? '',
            atcCode2: $data['atcCode2'] ?? null,
            packageSize: (int)($data['packageSize'] ?? 0),
            strength: $data['strength'] ?? '',
            shelfLife: (int)($data['shelfLife'] ?? 0),
            price: $data['price'] ?? '0',
            packageType: $packageType,
            pharmaceuticalFormAr: $pharmaForm['nameAr'] ?? null,
            pharmaceuticalFormEn: $pharmaForm['nameEn'] ?? null,
            storageConditionAr: $storage['nameAr'] ?? null,
            storageConditionEn: $storage['nameEn'] ?? null,
            marketingStatusAr: $marketing['nameAr'] ?? null,
            marketingStatusEn: $marketing['nameEn'] ?? null,
            legalStatusAr: $legal['nameAr'] ?? null,
            legalStatusEn: $legal['nameEn'] ?? null,
            companyNameEn: $company['nameEn'] ?? null,
            companyCountryEn: $company['country']['nameEn'] ?? null,
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
