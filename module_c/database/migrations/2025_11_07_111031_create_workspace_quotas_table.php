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
        Schema::create('workspace_quotas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('workspace_id')->unsigned()->unique();
            $table->decimal('max_amount', 10, 4)->default(100.0000)->comment('Max spending limit for the workspace');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_quotas');
    }
};
