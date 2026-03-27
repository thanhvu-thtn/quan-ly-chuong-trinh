@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">📚 Quản lý Nội dung</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('contents.create', request()->query()) }}" class="btn btn-success fw-bold shadow-sm">
                    ➕ Thêm mới
                </a>
                <a href="{{ route('contents.export-word-all', request()->query()) }}"
                    class="btn btn-primary fw-bold shadow-sm">
                    📄 Xuất Word
                </a>
                <a href="{{ route('contents.export-pdf-all', request()->query()) }}" class="btn btn-danger fw-bold shadow-sm">
                    📕 Xuất PDF
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body bg-light rounded">
                <form action="{{ route('contents.index') }}" method="GET" class="row g-2 align-items-end">

                    <div class="col-md-2">
                        <label class="form-label fw-bold text-muted small mb-1">Loại</label>
                        <select name="topic_type_id" id="filter_topic_type_id" class="form-select">
                            <option value="">-- Tất cả Loại --</option>
                            @foreach ($topicTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('topic_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold text-muted small mb-1">Khối</label>
                        <select name="grade" id="filter_grade" class="form-select">
                            <option value="">-- Tất cả --</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>
                                    Khối {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small mb-1">Chủ đề</label>
                        <select name="topic_id" id="filter_topic_id" class="form-select">
                            <option value="">-- Tất cả Chủ đề --</option>
                            @foreach ($topics as $topic)
                                <option value="{{ $topic->id }}" data-grade="{{ $topic->grade }}"
                                    data-type="{{ $topic->topic_type_id }}"
                                    {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                    Khối {{ $topic->grade }} - {{ $topic->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary w-50 fw-bold shadow-sm">🔍 Lọc</button>
                            <a href="{{ route('contents.index') }}" class="btn btn-outline-secondary w-50 shadow-sm">🔄
                                Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle m-0">
                        <thead class="table-primary text-center">
                            <tr>
                                <th width="8%">Khối</th>
                                <th width="15%">Loại</th>
                                <th width="25%">Chủ đề</th>
                                <th>Nội dung</th>
                                <th width="15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contents as $content)
                                <tr>
                                    <td class="text-center fw-bold text-danger">Khối {{ $content->topic->grade }}</td>
                                    <td class="text-center"><span
                                            class="badge bg-secondary">{{ $content->topic->topicType->name }}</span></td>
                                    <td>{{ $content->topic->name }}</td>
                                    <td class="fw-bold text-primary">{{ $content->name }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('contents.show', array_merge([$content->id], request()->query())) }}"
                                                class="btn btn-sm btn-info text-white" title="Xem chi tiết">👁️</a>

                                            <a href="{{ route('contents.edit', array_merge([$content->id], request()->query())) }}"
                                                class="btn btn-sm btn-warning text-dark" title="Sửa">✏️</a>

                                            <a href="{{ route('contents.delete', array_merge([$content->id], request()->query())) }}"
                                                class="btn btn-sm btn-danger" title="Xóa">🗑️ Xóa</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Không tìm thấy dữ liệu nào phù hợp.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white pt-3">
                {{ $contents->links() }}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gradeSelect = document.getElementById('filter_grade');
            const typeSelect = document.getElementById('filter_topic_type_id');
            const topicSelect = document.getElementById('filter_topic_id');

            // 1. Sao chép và lưu trữ toàn bộ các <option> của Chủ đề khi trang vừa tải xong
            // Việc này giúp ta có sẵn dữ liệu gốc để nhét lại vào thẻ select mà không cần gọi server
            const originalTopicOptions = Array.from(topicSelect.options);

            function updateTopicDropdown() {
                const selectedGrade = gradeSelect.value;
                const selectedType = typeSelect.value;
                const currentSelectedTopic = topicSelect.value; // Ghi nhớ chủ đề đang chọn (nếu có)

                // 2. Dọn sạch hộp thoại Chủ đề hiện tại
                topicSelect.innerHTML = '';

                // 3. Quét qua danh sách gốc, cái nào khớp thì mới "nhét" lại vào giao diện
                originalTopicOptions.forEach(opt => {
                    // Luôn giữ lại option đầu tiên ("-- Tất cả Chủ đề --")
                    if (opt.value === "") {
                        topicSelect.appendChild(opt.cloneNode(true));
                        return;
                    }

                    const matchGrade = (selectedGrade === "" || opt.getAttribute('data-grade') ===
                        selectedGrade);
                    const matchType = (selectedType === "" || opt.getAttribute('data-type') ===
                        selectedType);

                    // Nếu khớp Khối VÀ khớp Loại thì hiển thị
                    if (matchGrade && matchType) {
                        topicSelect.appendChild(opt.cloneNode(true));
                    }
                });

                // 4. Giữ lại lựa chọn của người dùng nếu option đó vẫn còn tồn tại sau khi lọc
                if (Array.from(topicSelect.options).some(opt => opt.value === currentSelectedTopic)) {
                    topicSelect.value = currentSelectedTopic;
                } else {
                    topicSelect.value = ""; // Nếu bị lọc mất rồi thì reset về "-- Tất cả --"
                }
            }

            // Lắng nghe sự kiện thay đổi trên Khối và Loại
            gradeSelect.addEventListener('change', updateTopicDropdown);
            typeSelect.addEventListener('change', updateTopicDropdown);

            // Chạy lần đầu tiên lúc load trang để dọn dẹp danh sách dựa trên URL (nếu có)
            updateTopicDropdown();
        });
    </script>
@endsection
