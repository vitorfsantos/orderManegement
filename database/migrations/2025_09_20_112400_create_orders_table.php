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
      $table->uuid('id')->primary();
      $table->string('code')->unique();
      $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
      $table->decimal('total', 10, 2);
      $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
      $table->timestamps();
      $table->softDeletes();
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
