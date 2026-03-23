@extends('layouts.app')

@section('title', 'Danh sách Chuyên đề')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">📘 Danh sách Chuyên đề</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('topics.export.pdf', ['grade' => $selectedGrade, 'topic_type_id' => $selectedTopicType]) }}"
                class="btn btn-sm btn-outline-danger me-2 fw-bold">
                📄 Xuất PDF
            </a>
            <a href="{{ route('topics.export.word', ['grade' => $selectedGrade, 'topic_type_id' => $selectedTopicType]) }}"
                class="btn btn-sm btn-outline-info me-2 fw-bold text-dark">
                📝 Xuất Word
            </a>
            <a href="{{ route('topics.create') }}" class="btn btn-sm btn-primary fw-bold">
                + Thêm chuyên đề mới
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4 border-0 bg-light">
        <div class="card-body p-3">
            <form action="{{ route('topics.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label fw-bold">Bộ lọc:</label>
                </div>

                <div class="col-auto">
                    <select name="grade" id="grade" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Tất cả khối --</option>
                        <option value="10" {{ $selectedGrade == '10' ? 'selected' : '' }}>Khối 10</option>
                        <option value="11" {{ $selectedGrade == '11' ? 'selected' : '' }}>Khối 11</option>
                        <option value="12" {{ $selectedGrade == '12' ? 'selected' : '' }}>Khối 12</option>
                    </select>
                </div>

                <div class="col-auto">
                    <select name="topic_type_id" id="topic_type_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Tất cả phân loại --</option>
                        @foreach ($topicTypes as $type)
                            <option value="{{ $type->id }}" {{ $selectedTopicType == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    @if ($selectedGrade || $selectedTopicType)
                        <a href="{{ route('topics.index') }}" class="btn btn-outline-secondary btn-sm">❌ Xóa bộ lọc</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 8%;">Khối</th>
                            <th class="text-center" style="width: 8%;">Thứ tự</th>
                            <th style="width: 34%;">Tên chuyên đề</th>
                            <th class="text-center" style="width: 15%;">Phân loại</th>
                            <th class="text-center" style="width: 10%;">Số tiết</th>
                            <th class="text-center" style="width: 25%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topics as $topic)
                            <tr>
                                <td class="text-center align-middle fw-bold text-danger">Lớp {{ $topic->grade }}</td>
                                <td class="text-center align-middle">{{ $topic->order }}</td>
                                <td class="align-middle fw-bold text-primary">{{ $topic->name }}</td>
                                <td class="align-middle">
                                    <span class="badge bg-info text-dark">{{ $topic->topicType->name }}</span>
                                </td>
                                <td class="text-center align-middle">{{ $topic->total_periods }} tiết</td>
                                <td class="text-center align-middle text-nowrap">
                                    <a href="{{ route('topics.show', $topic->id) }}"
                                        class="btn btn-sm btn-info fw-bold text-dark">👁️ Chi tiết</a>
                                    <a href="{{ route('topics.edit', $topic->id) }}"
                                        class="btn btn-sm btn-warning fw-bold">✏️ Sửa</a>
                                    <a href="#" class="btn btn-sm btn-danger fw-bold">🗑️ Xóa</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Chưa có chuyên đề nào trong hệ thống.
                                </td>
                            </tr>
                        @endforelse
                        @if ($topics->count() > 0)
                            <tr class="table-warning">
                                <td colspan="4" class="text-end pe-3 fw-bold fst-italic">
                                    Tổng cộng: {{ $topics->count() }} chuyên đề
                                </td>
                                <td class="text-center align-middle fw-bold text-danger">
                                    {{ $topics->sum('total_periods') }} tiết
                                </td>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
