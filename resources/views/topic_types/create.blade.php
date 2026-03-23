@extends('layouts.app')

@section('title', 'Thêm loại chuyên đề')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Thêm Loại chuyên đề mới</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('topic-types.index') }}" class="btn btn-sm btn-outline-secondary">
                ⬅ Trở về danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    Thông tin Loại chuyên đề
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('topic-types.store') }}" method="POST">
                        @csrf <div class="mb-3">
                            <label for="name" class="form-label">Tên loại chuyên đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Ngoại khóa, Tự chọn, STEM..." required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Mô tả (Tùy chọn)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Ghi chú thêm về loại chuyên đề này...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-light me-md-2">Nhập lại</button>
                            <button type="submit" class="btn btn-primary fw-bold">💾 Lưu dữ liệu</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection