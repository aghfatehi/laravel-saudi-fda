<?php

namespace Aghfatehi\SaudiFda\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ApiRequestSucceeded
{
    use Dispatchable;

    public function __construct(
        public string $service,
        public string $endpoint,
        public array $response,
        public float $duration,
    ) {}
}
