@extends('layouts.app')

@section('title', 'Quản lý Loại chuyên đề')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">📚 Danh sách Loại chuyên đề</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('topic-types.create') }}" class="btn btn-sm btn-primary fw-bold">
                + Thêm loại chuyên đề mới
            </a>
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
                            <th scope="col" class="text-center" style="width: 5%;">STT</th>
                            <th scope="col" style="width: 20%;">Tên loại</th>
                            <th scope="col">Mô tả</th>
                            <th scope="col" class="text-center" style="width: 15%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topicTypes as $key => $type)
                            <tr>
                                <td class="text-center align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle fw-bold text-primary">{{ $type->name }}</td>
                                <td class="align-middle">{{ $type->description ?? 'Không có mô tả' }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('topic-types.edit', $type->id) }}"
                                        class="btn btn-sm btn-warning">Sửa</a>

                                    <form action="{{ route('topic-types.destroy', $type->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('CẢNH BÁO NGUY HIỂM: Bạn đang chuẩn bị xóa loại chuyên đề ({{ $type->name }}). Hành động này sẽ XÓA SẠCH TOÀN BỘ các chuyên đề và nội dung thuộc loại này. Bạn có CHẮC CHẮN muốn xóa không?');">
                                        @csrf
                                        @method('DELETE') <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Chưa có loại chuyên đề nào trong hệ thống.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
