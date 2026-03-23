@extends('layouts.app')

@section('title', 'Sửa thông tin người dùng')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Sửa thông tin: <span class="text-primary">{{ $user->name }}</span></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                ⬅ Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark fw-bold">
                    Cập nhật tài khoản
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Phương thức PUT để cập nhật tài nguyên --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="userName" class="form-label">Tên đăng nhập <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('userName') is-invalid @enderror"
                                    id="userName" name="userName" value="{{ old('userName', $user->userName) }}" required>
                                @error('userName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label">Họ và tên <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Để trống nếu không muốn đổi">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Nhập lại mật khẩu mới</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Chỉ nhập khi đổi mật khẩu">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="label" class="form-label">Chức vụ (Tùy chọn)</label>
                                <input type="text" class="form-control @error('label') is-invalid @enderror"
                                    id="label" name="label" value="{{ old('label', $user->label) }}">
                                @error('label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="isAdmin" class="form-label">Quyền hạn <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('isAdmin') is-invalid @enderror" id="isAdmin"
                                    name="isAdmin">
                                    <option value="0" {{ old('isAdmin', $user->isAdmin) == '0' ? 'selected' : '' }}>
                                        Giáo viên (Mặc định)</option>
                                    <option value="1" {{ old('isAdmin', $user->isAdmin) == '1' ? 'selected' : '' }}>
                                        Quản trị viên (Admin)</option>
                                </select>
                                @error('isAdmin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-warning fw-bold">Cập nhật thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
