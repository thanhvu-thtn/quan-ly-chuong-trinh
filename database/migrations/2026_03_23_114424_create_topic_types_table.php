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
        Schema::create('topic_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên loại: Cơ bản, Nâng cao, Chuyên
            $table->string('description')->nullable(); // Mô tả thêm (Có thể để trống)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_types');
    }
};
