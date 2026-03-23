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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // userName: tên đăng nhập, bắt buộc, không được trùng lặp
            $table->string('userName')->unique(); 
            
            // name: Họ và tên, bắt buộc
            $table->string('name'); 
            
            // password: Mật khẩu để đăng nhập (Laravel Auth cần trường này)
            $table->string('password'); 
            
            // isAdmin: Có phải admin không, mặc định là false (0)
            $table->boolean('isAdmin')->default(false); 
            
            // label: Chức vụ, cho phép để trống (nullable)
            $table->string('label')->nullable(); 
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
