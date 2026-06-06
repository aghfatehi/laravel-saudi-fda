<?php

namespace Aghfatehi\SaudiFda\DTO;

class CosmeticsProductDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly string $cosmeticNumber,
        public readonly string $accountName,
        public readonly string $accountNameAr,
        public readonly string $crNumber,
        public readonly string $brandName,
        public readonly string $specificName,
        public readonly string $specificNameAr,
        public readonly string $mainCategoryAr,
        public readonly string $mainCategoryEn,
        public readonly string $firstSubCategoryAr,
        public readonly string $firstSubCategoryEn,
        public readonly string $secondSubCategoryAr,
        public readonly string $secondSubCategoryEn,
        public readonly string $expiryDate,
        public readonly bool $isActive,
        public readonly bool $isApproved,
        public readonly string $status,
        public readonly string $productType,
        public readonly ?string $variantNumber = null,
        public readonly ?string $variationNameEn = null,
        public readonly ?string $variationNameAr = null,
        public readonly ?string $packageNumber = null,
        public readonly ?string $barCode = null,
        public readonly ?float $packageVolume = null,
        public readonly ?string $volumeUnitAr = null,
        public readonly ?string $volumeUnitEn = null,
        public readonly ?string $manufacturerNameEn = null,
        public readonly ?string $manufacturerNameAr = null,
        public readonly ?string $manufacturerNumber = null,
        public readonly ?string $manufacturerLicenseNumber = null,
        public readonly ?string $facilityType = null,
        public readonly ?string $manufacturerLocation = null,
        public readonly ?string $manufacturerCountryAr = null,
        public readonly ?string $manufacturerCountryEn = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: (int)($data['product_Id'] ?? 0),
            cosmeticNumber: $data['cosmeticNumber'] ?? '',
            accountName: $data['accountName'] ?? '',
            accountNameAr: $data['accountNameAr'] ?? '',
            crNumber: $data['crNumber'] ?? '',
            brandName: $data['brandName'] ?? '',
            specificName: $data['specificName'] ?? '',
            specificNameAr: $data['specificNameAr'] ?? '',
            mainCategoryAr: $data['mainCategoryAr'] ?? '',
            mainCategoryEn: $data['mainCategoryEn'] ?? '',
            firstSubCategoryAr: $data['firstSubCategoryAr'] ?? '',
            firstSubCategoryEn: $data['firstSubCategoryEn'] ?? '',
            secondSubCategoryAr: $data['secondSubCategoryAr'] ?? '',
            secondSubCategoryEn: $data['secondSubCategoryEn'] ?? '',
            expiryDate: $data['expiryDate'] ?? '',
            isActive: (bool)($data['isActive'] ?? false),
            isApproved: (bool)($data['isApproved'] ?? false),
            status: $data['status'] ?? '',
            productType: $data['productType'] ?? '',
            variantNumber: $data['variantNumber'] ?? null,
            variationNameEn: $data['variationNameEn'] ?? null,
            variationNameAr: $data['variationNameAr'] ?? null,
            packageNumber: $data['packageNumber'] ?? null,
            barCode: $data['barCode'] ?? null,
            packageVolume: isset($data['packageVolume']) ? (float)$data['packageVolume'] : null,
            volumeUnitAr: $data['volumeUnitAr'] ?? null,
            volumeUnitEn: $data['volumeUnitEn'] ?? null,
            manufacturerNameEn: $data['manufacturerNameEn'] ?? null,
            manufacturerNameAr: $data['manufacturerNameAr'] ?? null,
            manufacturerNumber: $data['manufacturerNumber'] ?? null,
            manufacturerLicenseNumber: $data['manufacturerLicenseNumber'] ?? null,
            facilityType: $data['facilityType'] ?? null,
            manufacturerLocation: $data['manufacturerLocation'] ?? null,
            manufacturerCountryAr: $data['manufacturerCountryAr'] ?? null,
            manufacturerCountryEn: $data['manufacturerCountryEn'] ?? null,
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
