@extends('layouts.app')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Danh sách người dùng</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                + Thêm người dùng
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="text-center">STT</th>
                    <th scope="col">Tên đăng nhập</th>
                    <th scope="col">Họ và tên</th>
                    <th scope="col">Chức vụ</th>
                    <th scope="col" class="text-center">Phân quyền</th>
                    <th scope="col" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                {{-- Vòng lặp lấy dữ liệu từ Controller truyền sang --}}
                @forelse ($users as $key => $user)
                    <tr>
        
                        <td class="text-center">
                            {{ $key + 1 }}
                        </td>
                        <td><strong>{{ $user->userName }}</strong></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->label ?? '---' }}</td>
                        
                        <td class="text-center">
                            {{-- Hiển thị badge màu sắc tùy theo quyền --}}
                            @if ($user->isAdmin)
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-secondary">Giáo viên</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
                            {{-- Nút Sửa --}}
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            
                            {{-- Nút Xóa (Cần dùng form để gửi request DELETE) --}}
                            <a href="{{ route('users.delete', $user->id) }}" class="btn btn-sm btn-danger">Xóa</a>
                        </td>
                    </tr>
                @empty
                    {{-- Hiển thị nếu database chưa có ai --}}
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Chưa có dữ liệu người dùng.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@endsection