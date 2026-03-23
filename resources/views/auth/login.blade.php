@extends('layouts.app')

@section('title', 'Đăng nhập hệ thống')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center fw-bold">
                ĐĂNG NHẬP HỆ THỐNG QLCT
            </div>
            <div class="card-body p-4">
                
                <form method="POST" action="#">
                    @csrf

                    <div class="mb-3">
                        <label for="userName" class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control @error('userName') is-invalid @enderror" id="userName" name="userName" value="{{ old('userName') }}" required autofocus>
                        @error('userName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Đăng nhập</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
@if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ⚠️ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endsection