<?php

namespace Aghfatehi\SaudiFda\DTO;

class MedicalDeviceDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $deviceNumber,
        public readonly ?string $accountNumber,
        public readonly ?string $registrationNumber,
        public readonly ?string $accountName,
        public readonly ?string $accountNameAr,
        public readonly ?string $crNumber,
        public readonly ?string $manufactureName,
        public readonly ?string $manufactureCountry,
        public readonly ?string $manufactureNumber,
        public readonly ?string $productNumber,
        public readonly ?string $productName,
        public readonly ?string $productType,
        public readonly ?string $genericName,
        public readonly ?string $description,
        public readonly ?string $intendedUse,
        public readonly ?string $status,
        public readonly ?string $issueDate,
        public readonly ?string $expiryDate,
        public readonly ?string $modelNumber,
        public readonly ?string $gmdn,
        public readonly ?string $brandName = null,
        public readonly ?string $referenceNumber = null,
        public readonly ?string $tradeName = null,
        public readonly ?string $type = null,
    ) {}

    public static function fromLowRiskData(array $data): self
    {
        return new self(
            id: (int)($data['id'] ?? 0),
            deviceNumber: $data['productNumber'] ?? null,
            accountNumber: $data['accountNumber'] ?? null,
            registrationNumber: $data['registrationNumber'] ?? null,
            accountName: $data['accountName'] ?? null,
            accountNameAr: $data['accountNameAr'] ?? null,
            crNumber: $data['crNumber'] ?? null,
            manufactureName: $data['manufactureName'] ?? null,
            manufactureCountry: $data['manufactureCountry'] ?? null,
            manufactureNumber: $data['manufactureNumber'] ?? null,
            productNumber: $data['productNumber'] ?? null,
            productName: $data['productName'] ?? null,
            productType: $data['productType'] ?? null,
            genericName: $data['genericName'] ?? null,
            description: $data['description'] ?? null,
            intendedUse: $data['intendedUse'] ?? null,
            status: null,
            issueDate: $data['issueDate'] ?? null,
            expiryDate: $data['expiryDate'] ?? null,
            modelNumber: $data['modelNbr'] ?? null,
            gmdn: $data['gmdnTerm'] ?? null,
            brandName: $data['brandName'] ?? null,
            referenceNumber: $data['referenceNumber'] ?? null,
            tradeName: $data['tradeName'] ?? null,
            type: 'LowRisk',
        );
    }

    public static function fromGHTFData(array $data): self
    {
        return new self(
            id: (int)($data['id'] ?? 0),
            deviceNumber: $data['deviceNumber'] ?? null,
            accountNumber: $data['accountNumber'] ?? null,
            registrationNumber: $data['registrationNumber'] ?? null,
            accountName: $data['accountName'] ?? null,
            accountNameAr: $data['accountNameAr'] ?? null,
            crNumber: $data['crNumber'] ?? null,
            manufactureName: $data['manufactureName'] ?? null,
            manufactureCountry: $data['manufactureCountry'] ?? null,
            manufactureNumber: $data['manufactureNumber'] ?? null,
            productNumber: $data['productNumber'] ?? null,
            productName: $data['productName'] ?? null,
            productType: $data['productType'] ?? null,
            genericName: $data['genericName'] ?? null,
            description: $data['description'] ?? null,
            intendedUse: $data['intendedUse'] ?? null,
            status: null,
            issueDate: $data['issueDate'] ?? null,
            expiryDate: $data['expiryDate'] ?? null,
            modelNumber: $data['modelNbr'] ?? null,
            gmdn: $data['gmdnTerm'] ?? null,
            brandName: $data['brandName'] ?? null,
            referenceNumber: $data['referenceNumber'] ?? null,
            tradeName: $data['tradeName'] ?? null,
            type: 'GHTF',
        );
    }

    public static function fromTFAData(array $data): self
    {
        return new self(
            id: (int)($data['id'] ?? 0),
            deviceNumber: $data['deviceNumber'] ?? null,
            accountNumber: $data['accountNumber'] ?? null,
            registrationNumber: null,
            accountName: $data['accountName'] ?? null,
            accountNameAr: $data['accountNameAr'] ?? null,
            crNumber: $data['crNumber'] ?? null,
            manufactureName: $data['manufacturerName'] ?? null,
            manufactureCountry: $data['manufacture_CountryEn'] ?? null,
            manufactureNumber: null,
            productNumber: $data['deviceNumber'] ?? null,
            productName: $data['brandName'] ?? null,
            productType: $data['type'] ?? null,
            genericName: null,
            description: $data['description'] ?? null,
            intendedUse: $data['intendedUse'] ?? null,
            status: $data['status'] ?? null,
            issueDate: $data['issueDate'] ?? null,
            expiryDate: $data['expiryDate'] ?? null,
            modelNumber: $data['modelNumber'] ?? null,
            gmdn: $data['gmdn'] ?? null,
            brandName: $data['brandName'] ?? null,
            referenceNumber: $data['referenceNumber'] ?? null,
            tradeName: $data['tradeName'] ?? null,
            type: 'TFA',
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
