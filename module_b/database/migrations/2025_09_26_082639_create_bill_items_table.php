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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("bill_id")->unsigned();
            $table->foreign("bill_id")->references("id")->on("bills");
            $table->bigInteger("token_id")->unsigned();
            $table->foreign("token_id")->references("id")->on("tokens");
            $table->string("service_name", 255);
            $table->float("duration");
            $table->float("price");
            $table->float("total_cost");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
