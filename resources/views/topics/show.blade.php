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
                    <strong>Tổng số tiết quy định:</strong> <span class="text-danger fw-bold">{{ $topic->total_periods }} tiết</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-secondary">
        <div class="card-header bg-secondary text-white fw-bold d-flex justify-content-between align-items-center">
            <span>📚 Danh sách nội dung</span>
            <a href="{{ route('contents.create', ['topic_id' => $topic->id]) }}" class="btn btn-sm btn-success fw-bold shadow-sm">
                ➕ Thêm Nội dung
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 10%;">STT</th>
                            <th style="width: 55%;">Nội dung</th>
                            <th class="text-center" style="width: 15%;">Số tiết</th>
                            <th class="text-center" style="width: 20%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sumPeriods = $topic->contents ? $topic->contents->sum('periods') : 0;
                            $totalPeriods = $topic->total_periods;
                            $diff = $sumPeriods - $totalPeriods;
                        @endphp

                        @if($topic->contents && $topic->contents->count() > 0)
                            @foreach($topic->contents as $index => $content)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $content->name }}</td>
                                    <td class="text-center">{{ $content->periods }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-sm btn-warning text-dark" title="Sửa">✏️ Sửa</a>
                                            <a href="{{ route('contents.delete', $content->id) }}" class="btn btn-sm btn-danger" title="Xóa">🗑️ Xóa</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            
                            <tr class="table-light">
                                <td colspan="2" class="text-end fw-bold fst-italic pe-3">Tổng số tiết đã phân bố:</td>
                                <td class="text-center fw-bold fs-5 text-primary">{{ $sumPeriods }}</td>
                                <td></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted fst-italic">Chưa có nội dung nào trong chuyên đề này.</td>
                            </tr>
                        @endif

                        @if($topic->contents && $topic->contents->count() > 0)
                            <tr>
                                <td colspan="4" class="text-center fw-bold py-3 
                                    {{ $diff == 0 ? 'bg-success text-white' : ($diff < 0 ? 'bg-warning text-dark' : 'bg-danger text-white') }}">
                                    @if($diff == 0)
                                        ✅ Hoàn hảo! Đã phân bổ khớp {{ $sumPeriods }}/{{ $totalPeriods }} tiết.
                                    @elseif($diff < 0)
                                        ⚠️ Chú ý: Còn thiếu {{ abs($diff) }} tiết chưa được phân bổ (Hiện tại: {{ $sumPeriods }}/{{ $totalPeriods }}).
                                    @else
                                        ❌ Cảnh báo: Số tiết phân bổ đã vượt quá quy định {{ $diff }} tiết (Hiện tại: {{ $sumPeriods }}/{{ $totalPeriods }}).
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection