@extends('layouts.app')

@section('title', 'Sửa Chuyên đề')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Sửa Chuyên đề</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('topics.index') }}" class="btn btn-sm btn-outline-secondary">
                ⬅ Trở về danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark fw-bold">
                    Cập nhật: {{ $topic->name }}
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('topics.update', $topic->id) }}" method="POST">
                        @csrf 
                        @method('PUT') <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên chuyên đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $topic->name) }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="grade" class="form-label fw-bold">Khối lớp <span class="text-danger">*</span></label>
                                <select class="form-select @error('grade') is-invalid @enderror" id="grade" name="grade" required>
                                    <option value="10" {{ old('grade', $topic->grade) == '10' ? 'selected' : '' }}>Khối 10</option>
                                    <option value="11" {{ old('grade', $topic->grade) == '11' ? 'selected' : '' }}>Khối 11</option>
                                    <option value="12" {{ old('grade', $topic->grade) == '12' ? 'selected' : '' }}>Khối 12</option>
                                </select>
                                @error('grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="topic_type_id" class="form-label fw-bold">Loại chuyên đề <span class="text-danger">*</span></label>
                                <select class="form-select @error('topic_type_id') is-invalid @enderror" id="topic_type_id" name="topic_type_id" required>
                                    @foreach($topicTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('topic_type_id', $topic->topic_type_id) == $type->id ? 'selected' : '' }}>
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
                                <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $topic->order) }}" min="1" required>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="total_periods" class="form-label fw-bold">Số tiết <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('total_periods') is-invalid @enderror" id="total_periods" name="total_periods" value="{{ old('total_periods', $topic->total_periods) }}" min="1" required>
                                @error('total_periods')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('topics.index') }}" class="btn btn-light me-md-2">Hủy bỏ</a>
                            <button type="submit" class="btn btn-warning fw-bold">🔄 Cập nhật</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection