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
        Schema::table('topics', function (Blueprint $table) {
            // Thêm cột 'order', kiểu số nguyên dương (unsignedInteger), mặc định là 1.
            // Dùng after('grade') để xếp cột này đứng ngay sau cột Khối lớp cho đẹp đội hình trong DB.
            $table->unsignedInteger('order')->default(1)->after('grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            // Hàm này dùng để xóa (hủy) cột nếu sau này bạn chạy lệnh rollback
            $table->dropColumn('order');
        });
    }
};
