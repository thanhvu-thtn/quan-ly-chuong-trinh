@extends('layouts.app')

@section('title', 'Sửa Nội dung')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Sửa Nội dung Chuyên đề</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('contents.index', request()->query()) }}" class="btn btn-sm btn-outline-secondary">⬅ Trở về</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark fw-bold">
                    ✏️ Cập nhật Nội dung
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('contents.update', $content->id) }}" method="POST">
                        @csrf 
                        @method('PUT')
                        
                        <input type="hidden" name="back_url" value="{{ old('back_url', $backUrl ?? route('contents.index')) }}">

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
                                            @foreach($topicTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="topic_id" class="form-label fw-bold">Chọn Chuyên đề <span class="text-danger">*</span></label>
                            <select class="form-select @error('topic_id') is-invalid @enderror" id="topic_id" name="topic_id" required>
                                <option value="">-- Vui lòng chọn chuyên đề --</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" 
                                            data-grade="{{ $topic->grade }}" 
                                            data-type="{{ $topic->topic_type_id }}"
                                            {{ old('topic_id', $content->topic_id) == $topic->id ? 'selected' : '' }}>
                                        [Lớp {{ $topic->grade }} - {{ $topic->topicType->name }}] Bài {{ $topic->order }}: {{ $topic->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('topic_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên nội dung <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $content->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="order" class="form-label fw-bold">Thứ tự hiển thị <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $content->order) }}" min="1" required>
                                @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="periods" class="form-label fw-bold">Số tiết <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('periods') is-invalid @enderror" id="periods" name="periods" value="{{ old('periods', $content->periods) }}" min="0" required>
                                @error('periods') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-warning fw-bold text-dark">💾 Cập nhật Nội dung</button>
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
            const topicOptions = Array.from(topicSelect.options).filter(opt => opt.value !== ""); 

            function applyFilter() {
                const selectedGrade = filterGrade.value;
                const selectedType = filterType.value;
                let isCurrentSelectionValid = false;

                topicOptions.forEach(opt => {
                    const matchGrade = (selectedGrade === "" || opt.getAttribute('data-grade') === selectedGrade);
                    const matchType = (selectedType === "" || opt.getAttribute('data-type') === selectedType);

                    if (matchGrade && matchType) {
                        opt.hidden = false;    
                        opt.disabled = false;  
                        if (opt.selected) isCurrentSelectionValid = true;
                    } else {
                        opt.hidden = true;     
                        opt.disabled = true;   
                    }
                });

                if (!isCurrentSelectionValid) {
                    topicSelect.value = "";
                }
            }

            // Tự động nhận diện Khối và Loại dựa trên Chuyên đề đang được chọn của Content
            if (topicSelect.value !== "") {
                const selectedOption = topicSelect.options[topicSelect.selectedIndex];
                if (selectedOption) {
                    filterGrade.value = selectedOption.getAttribute('data-grade');
                    filterType.value = selectedOption.getAttribute('data-type');
                    applyFilter();
                }
            }

            filterGrade.addEventListener('change', applyFilter);
            filterType.addEventListener('change', applyFilter);
        });
    </script>
@endsection