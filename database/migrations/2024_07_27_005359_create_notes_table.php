<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('notes', function (Blueprint $table) {
      $table->id();
      $table->text('text');
      $table->unsignedBigInteger('user_id')->nullable();
      $table->unsignedBigInteger('category_id')->nullable();
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('category_id')->references('id')->on('categories');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('notes');
  }
};
