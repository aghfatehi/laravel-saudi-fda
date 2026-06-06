<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sfda_api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->string('endpoint');
            $table->string('method')->default('GET');
            $table->integer('http_code')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->float('duration_ms')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index('service');
            $table->index('http_code');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sfda_api_logs');
    }
};
