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
        Schema::create('objectives', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại liên kết với bảng contents, tự động xóa nếu content bị xóa
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            // Cột chứa nội dung của yêu cầu cần đạt
            $table->text('description'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objectives');
    }
};
