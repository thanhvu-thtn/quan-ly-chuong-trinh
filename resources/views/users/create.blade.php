@extends('layouts.app')

@section('title', 'Thêm người dùng mới')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Thêm người dùng mới</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                ⬅ Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin tài khoản</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="userName" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('userName') is-invalid @enderror" id="userName" name="userName" value="{{ old('userName') }}" required autofocus>
                                @error('userName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="label" class="form-label">Chức vụ (Tùy chọn)</label>
                                <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label') }}" placeholder="VD: Giáo viên bộ môn">
                                @error('label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="isAdmin" class="form-label">Quyền hạn <span class="text-danger">*</span></label>
                                <select class="form-select @error('isAdmin') is-invalid @enderror" id="isAdmin" name="isAdmin">
                                    <option value="0" {{ old('isAdmin') == '0' ? 'selected' : '' }}>Giáo viên (Mặc định)</option>
                                    <option value="1" {{ old('isAdmin') == '1' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                                </select>
                                @error('isAdmin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-light me-md-2">Nhập lại</button>
                            <button type="submit" class="btn btn-success">Lưu người dùng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection