@extends('layouts.app')

@section('title', 'Xác nhận Xóa Nội dung')

@section('content')
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white fw-bold fs-5">
                    ⚠️ XÁC NHẬN XÓA DỮ LIỆU
                </div>
                <div class="card-body p-4 text-center">
                    <h4 class="text-danger mb-3">Bạn có chắc chắn muốn xóa nội dung này?</h4>
                    <p class="text-muted">Hành động này không thể hoàn tác. Mọi dữ liệu liên quan (nếu có) có thể bị ảnh hưởng.</p>
                    
                    <div class="card bg-light border-0 my-4 text-start">
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 fs-5">
                                <li class="mb-2"><strong>Tên nội dung:</strong> <span class="text-primary">{{ $content->name }}</span></li>
                                <li class="mb-2"><strong>Số tiết:</strong> {{ $content->periods }} tiết</li>
                                <li><strong>Thuộc chuyên đề:</strong> Khối {{ $content->topic->grade }} - {{ $content->topic->name }}</li>
                            </ul>
                        </div>
                    </div>

                    <form action="{{ route('contents.destroy', $content->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <input type="hidden" name="back_url" value="{{ route('contents.index', request()->query()) }}">

                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <a href="{{ route('contents.index', request()->query()) }}" class="btn btn-secondary fw-bold px-4">
                                ✖ Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-danger fw-bold px-4">
                                🗑️ Vâng, Xóa ngay!
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection