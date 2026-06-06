<?php

namespace Aghfatehi\SaudiFda\Models;

use Illuminate\Database\Eloquent\Model;

class SaudiFdaApiLog extends Model
{
    protected $table = 'sfda_api_logs';

    protected $fillable = [
        'service',
        'endpoint',
        'method',
        'http_code',
        'request_payload',
        'response_payload',
        'error_message',
        'duration_ms',
        'ip_address',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'duration_ms' => 'float',
    ];
}
