<?php

namespace Aghfatehi\SaudiFda\Exceptions;

class AuthenticationException extends SaudiFdaException
{
    public function __construct(
        string $message = 'SFDA authentication failed',
        int $code = 401,
        ?\Throwable $previous = null,
        ?array $context = null,
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
