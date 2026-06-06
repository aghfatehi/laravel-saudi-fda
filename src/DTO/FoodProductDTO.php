<?php

namespace Aghfatehi\SaudiFda\DTO;

class FoodProductDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $bayaNId,
        public readonly string $referanceNumber,
        public readonly string $submittedDate,
        public readonly string $lastActionDate,
        public readonly ?string $closeDate,
        public readonly string $arStatus,
        public readonly string $enStatus,
        public readonly bool $isClosed,
        public readonly ?string $barCode,
        public readonly ?string $brandName,
        public readonly ?string $tradeName,
        public readonly ?string $hsCode,
        public readonly ?int $itemWeight,
        public readonly ?string $unitNameAr,
        public readonly ?string $unitNameEn,
        public readonly ?string $arCOO,
        public readonly ?string $enCOO,
        public readonly ?string $arCOPacking,
        public readonly ?string $enCOPacking,
        public readonly ?string $arCOProduction,
        public readonly ?string $enCOProduction,
        public readonly ?string $arPackingType,
        public readonly ?string $enPackingType,
        public readonly ?string $shelfTimeAr,
        public readonly ?string $shelfTimeEn,
        public readonly ?string $storageTemperatureAr,
        public readonly ?string $storageTemperatureEn,
        public readonly ?string $companyName,
        public readonly ?string $ageGroupAr,
        public readonly ?string $ageGroupEn,
        public readonly ?string $arClearance,
        public readonly ?string $enClearance,
        public readonly ?string $arFoodGroup,
        public readonly ?string $enFoodGroup,
        public readonly ?string $subGroupName,
        public readonly ?string $itemDescription,
        public readonly ?string $ingredientsAr,
        public readonly ?string $ingredientsEn,
        public readonly ?string $warnings,
        public readonly ?string $arPackingTypeName = null,
        public readonly ?string $enPackingTypeName = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)($data['id'] ?? 0),
            bayaNId: isset($data['bayaN_ID']) ? (int)$data['bayaN_ID'] : null,
            referanceNumber: $data['referanceNumber'] ?? '',
            submittedDate: $data['submittedDate'] ?? '',
            lastActionDate: $data['lastActionDate'] ?? '',
            closeDate: $data['closeDate'] ?? null,
            arStatus: $data['arStatus'] ?? '',
            enStatus: $data['enStatus'] ?? '',
            isClosed: (bool)($data['isClosed'] ?? false),
            barCode: $data['barCode'] ?? null,
            brandName: $data['brandName'] ?? null,
            tradeName: $data['tradeName'] ?? null,
            hsCode: $data['hsCode'] ?? null,
            itemWeight: isset($data['itemWeight']) ? (int)$data['itemWeight'] : null,
            unitNameAr: $data['unitNameAr'] ?? null,
            unitNameEn: $data['unitNameEn'] ?? null,
            arCOO: $data['arCOO'] ?? null,
            enCOO: $data['enCOO'] ?? null,
            arCOPacking: $data['arCOPacking'] ?? null,
            enCOPacking: $data['enCOPacking'] ?? null,
            arCOProduction: $data['arCOProduction'] ?? null,
            enCOProduction: $data['enCOProduction'] ?? null,
            arPackingType: $data['arPackingType'] ?? null,
            enPackingType: $data['enPackingType'] ?? null,
            shelfTimeAr: $data['shelfTimeAr'] ?? null,
            shelfTimeEn: $data['shelfTimeEn'] ?? null,
            storageTemperatureAr: $data['storageTemperatureAr'] ?? null,
            storageTemperatureEn: $data['storageTemperatureEn'] ?? null,
            companyName: $data['companyName'] ?? null,
            ageGroupAr: $data['ageGroupAr'] ?? null,
            ageGroupEn: $data['ageGroupEn'] ?? null,
            arClearance: $data['arClearance'] ?? null,
            enClearance: $data['enClearance'] ?? null,
            arFoodGroup: $data['arFoodGroup'] ?? null,
            enFoodGroup: $data['enFoodGroup'] ?? null,
            subGroupName: $data['subGroupName'] ?? null,
            itemDescription: $data['itemDescription'] ?? null,
            ingredientsAr: $data['ingredientsAr'] ?? null,
            ingredientsEn: $data['ingredientsEn'] ?? null,
            warnings: $data['warnings'] ?? null,
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
