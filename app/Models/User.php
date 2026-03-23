<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Các trường cho phép gán dữ liệu hàng loạt (Mass assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userName',
        'name',
        'password',
        'isAdmin',
        'label',
    ];

    /**
     * Các trường bị ẩn đi khi model được chuyển thành mảng hoặc JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Ép kiểu dữ liệu tự động.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Tự động băm mật khẩu (Laravel 10+)
            'isAdmin' => 'boolean', // Đảm bảo trả về true/false thay vì 1/0
        ];
    }
}