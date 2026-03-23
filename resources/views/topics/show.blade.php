@extends('layouts.app')

@section('title', 'Chi tiết Chuyên đề')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Chi tiết Chuyên đề</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('topics.exportDetail.pdf', $topic->id) }}" class="btn btn-sm btn-outline-danger me-2 fw-bold">📄 Xuất PDF</a>
            <a href="{{ route('topics.exportDetail.word', $topic->id) }}" class="btn btn-sm btn-outline-info me-2 fw-bold text-dark">📝 Xuất Word</a>
            <a href="{{ route('topics.index') }}" class="btn btn-sm btn-outline-secondary">⬅ Trở về danh sách</a>
        </div>
    </div>

    <div class="card shadow-sm border-info mb-4">
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

    <div class="card shadow-sm border-secondary">
        <div class="card-header bg-secondary text-white fw-bold">
            📚 Danh sách nội dung
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">STT</th>
                            <th style="width: 30%;">Nội dung</th>
                            <th class="text-center" style="width: 10%;">Số tiết</th>
                            <th style="width: 55%;">Yêu cầu cần đạt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($topic->contents && $topic->contents->count() > 0)
                            @foreach($topic->contents as $index => $content)
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle fw-bold">{{ $content->name }}</td>
                                    <td class="text-center align-middle">{{ $content->periods }}</td>
                                    <td class="align-middle">{!! nl2br(e($content->objectives)) !!}</td>
                                </tr>
                            @endforeach
                            <tr class="table-warning">
                                <td colspan="2" class="text-end fw-bold fst-italic pe-3">Tổng cộng: {{ $topic->contents->count() }} nội dung</td>
                                <td class="text-center fw-bold text-danger">{{ $topic->contents->sum('periods') }} tiết</td>
                                <td></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted fst-italic">Chưa có nội dung nào.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection