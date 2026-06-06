<?php

namespace Aghfatehi\SaudiFda\Logging;

use Aghfatehi\SaudiFda\Models\SaudiFdaApiLog;
use Illuminate\Support\Facades\Log;

class SaudiFdaLogger
{
    private bool $enabled;
    private string $channel;
    private string $level;
    private bool $maskPii;
    private bool $databaseEnabled;

    public function __construct()
    {
        $this->enabled = config('saudi-fda.logging.enabled', true);
        $this->channel = config('saudi-fda.logging.channel', 'stack');
        $this->level = config('saudi-fda.logging.level', 'info');
        $this->maskPii = config('saudi-fda.logging.mask_pii', true);
        $this->databaseEnabled = config('saudi-fda.logging.database.enabled', false);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    public function logRequest(
        string $service,
        string $endpoint,
        string $method = 'GET',
        ?int $httpCode = null,
        array $requestPayload = null,
        array $responsePayload = null,
        ?string $errorMessage = null,
        ?float $durationMs = null,
        ?string $ipAddress = null,
    ): void {
        $this->log('info', "{$service}:{$endpoint}", [
            'service' => $service,
            'endpoint' => $endpoint,
            'http_code' => $httpCode,
            'duration_ms' => $durationMs,
        ]);

        if (!$this->databaseEnabled) {
            return;
        }

        try {
            SaudiFdaApiLog::create([
                'service' => $service,
                'endpoint' => $endpoint,
                'method' => $method,
                'http_code' => $httpCode,
                'request_payload' => $this->maskPii && $requestPayload ? $this->maskSensitiveData($requestPayload) : $requestPayload,
                'response_payload' => $this->maskPii && $responsePayload ? $this->maskSensitiveData($responsePayload) : $responsePayload,
                'error_message' => $errorMessage,
                'duration_ms' => $durationMs,
                'ip_address' => $ipAddress,
            ]);
        } catch (\Throwable $e) {
            Log::error("[SFDA] Failed to write API log to database: {$e->getMessage()}");
        }
    }

    private function log(string $level, string $message, array $context = []): void
    {
        if (!$this->enabled) {
            return;
        }

        if ($this->maskPii) {
            $context = $this->maskSensitiveData($context);
        }

        $context['_sfda'] = true;

        Log::channel($this->channel)->log($level, "[SFDA] {$message}", $context);
    }

    private function maskSensitiveData(array $data): array
    {
        $sensitiveKeys = ['consumer_key', 'consumer_secret', 'password', 'token', 'authorization', 'access_token'];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                foreach ($sensitiveKeys as $sk) {
                    if (stripos($key, $sk) !== false) {
                        $data[$key] = substr($value, 0, 4) . '****';
                        break;
                    }
                }
                if (strlen($value) > 1000 && !isset($data['_truncated'])) {
                    $data[$key] = '[TRUNCATED]';
                }
            } elseif (is_array($value)) {
                $data[$key] = $this->maskSensitiveData($value);
            }
        }

        return $data;
    }
}
