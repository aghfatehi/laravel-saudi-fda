<?php

namespace Aghfatehi\SaudiFda\DTO;

class AccessTokenDTO
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $tokenType,
        public readonly string $issuedAt,
        public readonly string $expiresIn,
    ) {}

    public static function fromResponse(object $data): self
    {
        return new self(
            accessToken: $data->access_token ?? '',
            tokenType: $data->token_type ?? 'Bearer',
            issuedAt: $data->issued_at ?? '',
            expiresIn: $data->expires_in ?? '86400',
        );
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'issued_at' => $this->issuedAt,
            'expires_in' => $this->expiresIn,
        ];
    }
}
