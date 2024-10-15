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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("product_name");
            $table->integer("quantity");
            $table->decimal("unit_price");
            $table->decimal("total");
            $table->string("payment_id")->nullable();
            $table->text("payment_url")->nullable();
            $table->enum("status", ["Pending",  "Paid", "Canceled"])->default("Pending");
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
