<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sử dụng updateOrCreate để tránh lỗi trùng lặp dữ liệu nếu bạn chạy seeder nhiều lần
        User::updateOrCreate(
            ['userName' => 'admin'], // Điều kiện tìm kiếm (nếu đã có userName là admin thì không tạo mới mà sẽ cập nhật)
            [
                'name' => 'Quản trị viên Hệ thống',
                'password' => Hash::make('123456'), // Mã hóa mật khẩu
                'isAdmin' => true,
                'label' => 'Hiệu trưởng / Tổ trưởng chuyên môn',
            ]
        );
    }
}
