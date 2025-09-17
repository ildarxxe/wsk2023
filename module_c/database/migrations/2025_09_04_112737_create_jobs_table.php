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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("workspace_id");
            $table->unsignedBigInteger("job_id");
            $table->enum("type", ['image_generation', 'upscale', 'zoom_in', 'zoom_out']);
            $table->string("status");
            $table->dateTime("started_at")->nullable();
            $table->dateTime("finished_at")->nullable();
            $table->string("local_image_url")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
