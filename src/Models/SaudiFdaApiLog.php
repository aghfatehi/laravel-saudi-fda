<?php

namespace Aghfatehi\SaudiFda\Models;

use Illuminate\Database\Eloquent\Model;

class SaudiFdaApiLog extends Model
{
    protected $table = 'sfda_api_logs';

    protected $guarded = [];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'http_code' => 'integer',
        'duration_ms' => 'float',
    ];
}
