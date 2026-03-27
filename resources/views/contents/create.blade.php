@extends('layouts.app')

@section('title', 'Thêm Nội dung mới')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Thêm Nội dung Chuyên đề</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('contents.index', request()->query()) }}" class="btn btn-sm btn-outline-secondary">⬅ Trở về</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white fw-bold">
                    📝 Khai báo Nội dung mới
                </div>
                <div class="card-body p-4">

                    <form action="{{ route('contents.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="back_url"
                            value="{{ old('back_url', $backUrl ?? route('contents.index')) }}">
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body py-2">
                                <label class="fw-bold text-muted mb-2"><small>🔍 BỘ LỌC TÌM NHANH CHUYÊN ĐỀ:</small></label>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <select id="filter_grade" class="form-select form-select-sm border-info">
                                            <option value="">-- Tất cả các khối --</option>
                                            <option value="10">Lớp 10</option>
                                            <option value="11">Lớp 11</option>
                                            <option value="12">Lớp 12</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <select id="filter_topic_type" class="form-select form-select-sm border-info">
                                            <option value="">-- Tất cả loại chuyên đề --</option>
                                            @foreach ($topicTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--
                        <div class="mb-3">
                            <label for="topic_id" class="form-label fw-bold">Chọn Chuyên đề <span
                                    class="text-danger">*</span></label>
                             <select class="form-select @error('topic_id') is-invalid @enderror" id="topic_id" name="topic_id" required>
                                <option value="">-- Vui lòng chọn chuyên đề --</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}" 
                                            data-grade="{{ $topic->grade }}" 
                                            data-type="{{ $topic->topic_type_id }}"
                                            {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                        [Lớp {{ $topic->grade }} - {{ $topic->topicType->name }}] Bài {{ $topic->order }}: {{ $topic->name }}
                                    </option>
                                    <option value="{{ $t->id }}" {{ (old('topic_id') ?? request('topic_id')) == $t->id ? 'selected' : '' }}>
                                @endforeach
                            </select>
                            @error('topic_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
 --}}
                        <div class="mb-3">
                            <label for="topic_id" class="form-label fw-bold">Chọn Chuyên đề <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('topic_id') is-invalid @enderror" id="topic_id"
                                name="topic_id" required>
                                <option value="">-- Vui lòng chọn chuyên đề --</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}" data-grade="{{ $topic->grade }}"
                                        data-type="{{ $topic->topic_type_id }}"
                                        {{ (old('topic_id') ?? request('topic_id')) == $topic->id ? 'selected' : '' }}>
                                        [Lớp {{ $topic->grade }} - {{ $topic->topicType->name }}] Bài
                                        {{ $topic->order }}: {{ $topic->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('topic_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên nội dung <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="VD: Khái niệm về từ trường..."
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="order" class="form-label fw-bold">Thứ tự hiển thị <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    id="order" name="order" value="{{ old('order', 1) }}" min="1" required>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="periods" class="form-label fw-bold">Số tiết <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('periods') is-invalid @enderror"
                                    id="periods" name="periods" value="{{ old('periods', 1) }}" min="0" required>
                                @error('periods')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



                        <hr class="my-4">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-light me-md-2">Nhập lại</button>
                            <button type="submit" class="btn btn-primary fw-bold">💾 Lưu Nội dung</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterGrade = document.getElementById('filter_grade');
            const filterType = document.getElementById('filter_topic_type');
            const topicSelect = document.getElementById('topic_id');
            const topicOptions = Array.from(topicSelect.options).filter(opt => opt.value !==
                ""); // Bỏ qua option mặc định

            function applyFilter() {
                const selectedGrade = filterGrade.value;
                const selectedType = filterType.value;
                let isCurrentSelectionValid = false;

                topicOptions.forEach(opt => {
                    // Kiểm tra xem Option này có khớp với Khối và Loại đang chọn không
                    const matchGrade = (selectedGrade === "" || opt.getAttribute('data-grade') ===
                        selectedGrade);
                    const matchType = (selectedType === "" || opt.getAttribute('data-type') ===
                        selectedType);

                    if (matchGrade && matchType) {
                        opt.hidden = false; // Hiện option
                        opt.disabled = false; // Cho phép chọn
                        if (opt.selected) isCurrentSelectionValid = true;
                    } else {
                        opt.hidden = true; // Ẩn option đi
                        opt.disabled = true; // Khóa không cho chọn
                    }
                });

                // Nếu chuyên đề đang chọn bị ẩn đi do bộ lọc, reset select về mặc định
                if (!isCurrentSelectionValid) {
                    topicSelect.value = "";
                }
            }

            // --- ĐOẠN CODE MỚI THÊM ĐỂ ĐỒNG BỘ ---
        // Nếu vừa vào trang mà đã có Topic được chọn (từ URL truyền sang)
        if (topicSelect.value !== "") {
            const selectedOption = topicSelect.options[topicSelect.selectedIndex];
            if (selectedOption) {
                // Đẩy giá trị ngược lên 2 bộ lọc
                filterGrade.value = selectedOption.getAttribute('data-grade');
                filterType.value = selectedOption.getAttribute('data-type');
                // Gọi áp dụng lọc để làm sạch các options khác
                applyFilter();
            }
        }
        // ------------------------------------

            // Lắng nghe sự kiện khi người dùng thay đổi bộ lọc
            filterGrade.addEventListener('change', applyFilter);
            filterType.addEventListener('change', applyFilter);
        });
    </script>
@endsection
