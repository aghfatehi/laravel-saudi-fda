<?php

namespace Aghfatehi\SaudiFda\Listeners;

use Aghfatehi\SaudiFda\Events\ApiRequestFailed;
use Aghfatehi\SaudiFda\Events\ApiRequestSucceeded;
use Aghfatehi\SaudiFda\Logging\SaudiFdaLogger;

class LogApiRequestToDatabase
{
    public function __construct(private SaudiFdaLogger $logger) {}

    public function handleSucceeded(ApiRequestSucceeded $event): void
    {
        if (! config('saudi-fda.logging.database.enabled', false)) {
            return;
        }

        $this->logger->logRequest(
            service: $event->service,
            endpoint: $event->endpoint,
            method: 'GET',
            httpCode: 200,
            responsePayload: $event->response,
            durationMs: $event->duration * 1000,
        );
    }

    public function handleFailed(ApiRequestFailed $event): void
    {
        if (! config('saudi-fda.logging.database.enabled', false)) {
            return;
        }

        $this->logger->logRequest(
            service: $event->service,
            endpoint: $event->endpoint,
            method: 'GET',
            httpCode: $event->httpCode === 0 ? null : $event->httpCode,
            errorMessage: $event->errorMessage,
        );
    }

    public function subscribe(\Illuminate\Events\Dispatcher $events): void
    {
        $events->listen(
            ApiRequestSucceeded::class,
            [self::class, 'handleSucceeded']
        );

        $events->listen(
            ApiRequestFailed::class,
            [self::class, 'handleFailed']
        );
    }
}
