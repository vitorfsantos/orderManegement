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
    Schema::create('order_items', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->foreignUuid('order_id')->constrained()->onDelete('cascade');
      $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
      $table->integer('quantity');
      $table->decimal('unit_price', 10, 2);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('order_items');
  }
};
