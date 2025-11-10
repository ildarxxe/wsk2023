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
        Schema::create('billing_quotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("workspace_id");
            $table->foreign("workspace_id")->references("id")->on("workspaces");
            $table->decimal("limit")->nullable();
            $table->decimal("current_quota")->default(0.0);
            $table->integer("remaining_days");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_quotas');
    }
};
