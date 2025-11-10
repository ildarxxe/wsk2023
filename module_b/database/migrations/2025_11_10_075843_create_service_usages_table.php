<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_usages', function (Blueprint $table) {
            $table->id();
            $table->string("username");
            $table->string("workspace_title");
            $table->string("api_token_name");
            $table->decimal("usage_duration_in_ms");
            $table->timestamp("usage_started_at");
            $table->string("service_name");
            $table->decimal("service_cost_per_ms", 12, 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_usages');
    }
};
