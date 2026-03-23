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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
        
            // Khóa ngoại liên kết với bảng topic_types
            // cascadeOnDelete(): Nếu xóa 'Loại chuyên đề', các chuyên đề thuộc loại đó cũng bị xóa theo
            $table->foreignId('topic_type_id')->constrained('topic_types')->cascadeOnDelete();
        
            $table->string('name'); // Tên chuyên đề
            $table->tinyInteger('grade'); // Khối lớp: 10, 11, hoặc 12
            $table->integer('total_periods'); // Tổng số tiết được phân bổ
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
