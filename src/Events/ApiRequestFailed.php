<?php

namespace Aghfatehi\SaudiFda\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ApiRequestFailed
{
    use Dispatchable;

    public function __construct(
        public string $service,
        public string $endpoint,
        public string $errorMessage,
        public int $httpCode,
    ) {}
}
