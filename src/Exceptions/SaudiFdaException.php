<?php

namespace Aghfatehi\SaudiFda\Exceptions;

use Exception;

class SaudiFdaException extends Exception
{
    public function __construct(
        string $message = 'SFDA integration error',
        int $code = 0,
        ?\Throwable $previous = null,
        private ?array $context = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): ?array
    {
        return $this->context;
    }
}
