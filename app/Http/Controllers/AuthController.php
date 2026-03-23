<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    /**
     * Hiển thị giao diện Đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào
        $credentials = $request->validate([
            'userName' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'userName.required' => 'Vui lòng nhập tên đăng nhập.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // 2. Thử đăng nhập bằng Auth::attempt()
        // Hàm này tự động tìm userName trong DB và so sánh mật khẩu đã băm (hash)
        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công: Tạo lại session để bảo mật chống lại hacker (Session Fixation)
            $request->session()->regenerate();

            // Chuyển hướng người dùng vào trang chủ hoặc trang quản lý người dùng
            return redirect()->intended('/users')->with('success', 'Đăng nhập thành công!');
        }

        // 3. Đăng nhập thất bại: Trả về form đăng nhập kèm lỗi
        return back()->withErrors([
            'userName' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
        ])->onlyInput('userName'); // Giữ lại tên đăng nhập để người dùng không phải gõ lại
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        // Đăng xuất người dùng
        Auth::logout();
        // Hủy session hiện tại
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'Đăng xuất thành công!');

    }
}
