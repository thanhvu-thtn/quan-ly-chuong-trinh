@extends('layouts.app')

@section('title', 'Thêm Chuyên đề mới')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Thêm Chuyên đề mới</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('topics.index') }}" class="btn btn-sm btn-outline-secondary">
                ⬅ Trở về danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white fw-bold">
                    Thông tin Chuyên đề
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('topics.store') }}" method="POST">
                        @csrf 

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên chuyên đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Động học, Trái Đất và bầu trời..." required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="grade" class="form-label fw-bold">Khối lớp <span class="text-danger">*</span></label>
                                <select class="form-select @error('grade') is-invalid @enderror" id="grade" name="grade" required>
                                    <option value="" disabled {{ old('grade') ? '' : 'selected' }}>-- Chọn khối lớp --</option>
                                    <option value="10" {{ old('grade') == '10' ? 'selected' : '' }}>Khối 10</option>
                                    <option value="11" {{ old('grade') == '11' ? 'selected' : '' }}>Khối 11</option>
                                    <option value="12" {{ old('grade') == '12' ? 'selected' : '' }}>Khối 12</option>
                                </select>
                                @error('grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="topic_type_id" class="form-label fw-bold">Loại chuyên đề <span class="text-danger">*</span></label>
                                <select class="form-select @error('topic_type_id') is-invalid @enderror" id="topic_type_id" name="topic_type_id" required>
                                    <option value="" disabled {{ old('topic_type_id') ? '' : 'selected' }}>-- Chọn loại --</option>
                                    @foreach($topicTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('topic_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('topic_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="order" class="form-label fw-bold">Thứ tự bài <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 1) }}" min="1" required>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="total_periods" class="form-label fw-bold">Số tiết <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('total_periods') is-invalid @enderror" id="total_periods" name="total_periods" value="{{ old('total_periods', 1) }}" min="1" required>
                                @error('total_periods')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-light me-md-2">Nhập lại</button>
                            <button type="submit" class="btn btn-primary fw-bold">💾 Lưu Chuyên đề</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection