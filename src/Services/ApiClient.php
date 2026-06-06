<?php

namespace Aghfatehi\SaudiFda\Services;

use Aghfatehi\SaudiFda\Exceptions\AuthenticationException;
use Aghfatehi\SaudiFda\Exceptions\SaudiFdaException;
use Aghfatehi\SaudiFda\Logging\SaudiFdaLogger;

class ApiClient
{
    private int $timeout;
    private int $retryCount;
    private ?string $accessToken = null;
    private bool $isRefreshing = false;

    private ?\Closure $tokenRefreshCallback = null;

    public function __construct(
        private SaudiFdaLogger $logger,
    ) {
        $this->timeout = (int)config('saudi-fda.api.timeout', 60);
        $this->retryCount = (int)config('saudi-fda.api.retry_on_401', 1);
    }

    public function setAccessToken(?string $token): void
    {
        $this->accessToken = $token;
    }

    public function hasAccessToken(): bool
    {
        return $this->accessToken !== null;
    }

    public function setTokenRefreshCallback(\Closure $callback): void
    {
        $this->tokenRefreshCallback = $callback;
    }

    public function get(string $baseUrl, string $endpoint, array $query = []): object
    {
        $url = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $this->ensureToken();

        return $this->sendWithRetry(function () use ($url) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPGET => true,
                CURLOPT_HTTPHEADER => $this->buildHeaders(),
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ]);
            return $ch;
        }, $url);
    }

    public function post(string $baseUrl, string $endpoint, array $data = [], array $customHeaders = []): object
    {
        $url = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');

        $this->ensureToken();

        return $this->sendWithRetry(function () use ($url, $data, $customHeaders) {
            $ch = curl_init();
            $headers = array_merge($this->buildHeaders(), $customHeaders);
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ]);
            return $ch;
        }, $url);
    }

    public function postForm(string $baseUrl, string $endpoint, array $formData, array $customHeaders = []): object
    {
        $url = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');

        $this->ensureToken();

        return $this->sendWithRetry(function () use ($url, $formData, $customHeaders) {
            $ch = curl_init();
            $headers = array_merge([
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
                'Cache-Control: no-cache',
            ], $customHeaders);
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($formData),
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ]);
            return $ch;
        }, $url);
    }

    private function ensureToken(): void
    {
        if ($this->accessToken === null && $this->tokenRefreshCallback !== null && !$this->isRefreshing) {
            $this->isRefreshing = true;
            try {
                ($this->tokenRefreshCallback)();
            } finally {
                $this->isRefreshing = false;
            }
        }
    }

    private function sendWithRetry(\Closure $buildCurl, string $url, int $attempt = 0): object
    {
        $this->logger->info('SFDA API request', [
            'url' => $this->maskUrl($url),
            'attempt' => $attempt + 1,
        ]);

        $ch = $buildCurl();
        $start = microtime(true);
        $response = curl_exec($ch);
        $duration = microtime(true) - $start;
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new SaudiFdaException("SFDA API connection failed: {$curlError}");
        }

        $decoded = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new SaudiFdaException('Invalid JSON response from SFDA API');
        }

        if ($httpCode === 401 && $this->tokenRefreshCallback !== null && $attempt < $this->retryCount) {
            ($this->tokenRefreshCallback)();
            return $this->sendWithRetry($buildCurl, $url, $attempt + 1);
        }

        if ($httpCode === 401) {
            $errorMsg = $decoded->message ?? 'Unauthorized';
            throw new AuthenticationException($errorMsg, $httpCode);
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            $errorMsg = $decoded->message ?? $decoded->error ?? 'Unknown error';
            throw new SaudiFdaException("({$httpCode}) {$errorMsg}", $httpCode);
        }

        $this->logger->info('SFDA API success', [
            'http_code' => $httpCode,
            'duration_ms' => round($duration * 1000, 2),
        ]);

        return $decoded;
    }

    private function buildHeaders(): array
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Cache-Control: no-cache',
        ];

        if ($this->accessToken) {
            $headers[] = "Authorization: Bearer {$this->accessToken}";
        }

        return $headers;
    }

    private function maskUrl(string $url): string
    {
        return preg_replace('/Bearer\s+\S+/i', 'Bearer ***', $url);
    }
}
