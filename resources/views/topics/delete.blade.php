@extends('layouts.app')

@section('title', 'Xác nhận Xóa Chuyên đề')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2 text-danger">⚠️ Xác nhận Xóa Chuyên đề</h1>
    </div>

    <div class="alert alert-danger shadow-sm fs-5">
        <strong>CẢNH BÁO:</strong> Bạn đang thao tác xóa chuyên đề <strong>"{{ $topic->name }}"</strong>. 
        Việc này sẽ xóa toàn bộ <strong>{{ $topic->contents->count() }}</strong> nội dung thuộc về chuyên đề này. Hành động này không thể hoàn tác!
    </div>

    <div class="card shadow-sm border-danger mb-4">
        <div class="card-header bg-danger text-white fw-bold">
            Thông tin chuyên đề sắp xóa
        </div>
        <div class="card-body">
            <div class="row fs-5 text-center">
                <div class="col-md-4 mb-2">
                    <strong>Chuyên đề:</strong> <span class="text-primary fw-bold">{{ $topic->name }}</span>
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Loại chuyên đề:</strong> {{ $topic->topicType->name }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Tổng số tiết:</strong> <span class="text-danger fw-bold">{{ $topic->total_periods }} tiết</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-secondary mb-4">
        <div class="card-header bg-secondary text-white fw-bold">
            📚 Các nội dung sẽ bị xóa cùng
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">STT</th>
                            <th style="width: 30%;">Nội dung</th>
                            <th class="text-center" style="width: 10%;">Số tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topic->contents as $index => $content)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">{{ $content->name }}</td>
                                <td class="text-center align-middle">{{ $content->periods }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted fst-italic">Chuyên đề này chưa có nội dung nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-5">
        <a href="{{ route('topics.index') }}" class="btn btn-secondary me-3 px-4 fw-bold">⬅ Quay lại danh sách (Hủy)</a>
        
        <form action="{{ route('topics.destroy', $topic->id) }}" method="POST" onsubmit="return confirm('THAO TÁC NGUY HIỂM!\nBạn có chắc chắn muốn xóa vĩnh viễn chuyên đề này không?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger px-4 fw-bold">🗑️ Tôi hiểu nguy hiểm, XÓA!</button>
        </form>
    </div>
@endsection