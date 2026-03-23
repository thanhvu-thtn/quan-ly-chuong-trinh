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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
        
            // Khóa ngoại liên kết với bảng topics
            // cascadeOnDelete(): Xóa 1 chuyên đề thì toàn bộ nội dung con của nó bay màu theo
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
        
            $table->string('name'); // Tên nội dung
            $table->text('objectives'); // Yêu cầu cần đạt
            $table->integer('periods'); // Số tiết thực hiện nội dung này
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
