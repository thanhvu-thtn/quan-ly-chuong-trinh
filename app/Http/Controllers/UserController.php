<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy toàn bộ danh sách người dùng từ database
        $users = User::all();
        // Trả về view 'users.index' và truyền biến $users sang
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //trả về view 'users.create' để hiển thị form tạo người dùng mới
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Kiểm tra tính hợp lệ của dữ liệu (Validation)
        $request->validate([
            // userName: Bắt buộc, tối đa 50 ký tự, và không được trùng (unique) trong bảng users
            'userName' => 'required|string|max:50|unique:users,userName',
            
            // name: Bắt buộc, tối đa 255 ký tự
            'name' => 'required|string|max:255',
            
            // password: Bắt buộc, ít nhất 6 ký tự, và phải khớp với trường password_confirmation
            'password' => 'required|string|min:6|confirmed',
            
            // isAdmin: Bắt buộc, phải là kiểu boolean (true/false hoặc 1/0)
            'isAdmin' => 'required|boolean',
            
            // label: Không bắt buộc (nullable), tối đa 255 ký tự
            'label' => 'nullable|string|max:255',
        ], [
            // Tùy chỉnh thông báo lỗi sang tiếng Việt (Tùy chọn)
            'userName.required' => 'Vui lòng nhập tên đăng nhập.',
            'userName.unique' => 'Tên đăng nhập này đã có người sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'name.required' => 'Vui lòng nhập họ và tên.'
        ]);

        // 2. Tạo người dùng mới và lưu vào cơ sở dữ liệu
        User::create([
            'userName' => $request->userName,
            'name'     => $request->name,
            'password' => bcrypt($request->password), // Mã hóa mật khẩu trước khi lưu
            'isAdmin'  => $request->isAdmin,
            'label'    => $request->label,
        ]);

        // 3. Chuyển hướng về trang danh sách kèm thông báo thành công
        return redirect()->route('users.index')
                         ->with('success', 'Người dùng mới đã được tạo thành công.');
    }   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Tìm người dùng theo ID, nếu không thấy sẽ tự động báo lỗi 404
        $user = User::findOrFail($id); 
        
        // Truyền biến $user sang view edit.blade.php
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // 1. Kiểm tra dữ liệu (Validate)
        $request->validate([
            // unique:users,userName,{$id} -> Cho phép giữ nguyên userName cũ của chính user này mà không báo lỗi trùng lặp
            'userName' => 'required|string|max:50|unique:users,userName,' . $id,
            'name'     => 'required|string|max:255',
            // Mật khẩu lúc này là 'nullable' (có thể để trống)
            'password' => 'nullable|string|min:6|confirmed',
            'isAdmin'  => 'required|boolean',
            'label'    => 'nullable|string|max:255',
        ], [
            'userName.required' => 'Vui lòng nhập tên đăng nhập.',
            'userName.unique' => 'Tên đăng nhập này đã có người sử dụng.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'name.required' => 'Vui lòng nhập họ và tên.'
        ]);

        // 2. Chuẩn bị mảng dữ liệu để cập nhật
        $data = [
            'userName' => $request->userName,
            'name'     => $request->name,
            'isAdmin'  => $request->isAdmin,
            'label'    => $request->label,
        ];

        // 3. Xử lý riêng phần mật khẩu: Chỉ cập nhật nếu người dùng có nhập mật khẩu mới
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 4. Lưu vào database
        $user->update($data);

        // 5. Chuyển hướng và thông báo
        return redirect()->route('users.index')
                         ->with('success', 'Thông tin người dùng đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Người dùng đã được xóa thành công.');
    }
}
