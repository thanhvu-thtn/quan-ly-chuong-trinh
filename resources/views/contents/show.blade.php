@extends('layouts.app')

@section('title', 'Chi tiết Nội dung')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Chi tiết Nội dung</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-sm btn-warning me-2 fw-bold text-dark">✏️ Sửa
                nội dung</a>
            <a href="{{ route('contents.index', request()->query()) }}" class="btn btn-sm btn-outline-secondary">⬅ Trở về danh
                sách</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">

            <div class="card shadow-sm border-info mb-4">
                <div class="card-header bg-info text-dark fw-bold">
                    📌 Thông tin Chuyên đề trực thuộc
                </div>
                <div class="card-body">
                    <div class="row fs-6">
                        <div class="col-md-3 mb-2">
                            <strong>Khối lớp:</strong> <span class="text-primary fw-bold">Lớp
                                {{ $content->topic->grade }}</span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>Loại chuyên đề:</strong> {{ $content->topic->topicType->name }}
                        </div>
                        <div class="col-md-5 mb-2">
                            <strong>Tên chuyên đề:</strong> {{ $content->topic->name }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white fw-bold">
                    📖 Chi tiết Nội dung bài học
                </div>
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <tbody>
                            <tr>
                                <th style="width: 20%; background-color: #f8f9fa;">Thứ tự hiển thị:</th>
                                <td class="fw-bold">{{ $content->order }}</td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8f9fa;">Tên nội dung:</th>
                                <td class="fs-5 fw-bold text-success">{{ $content->name }}</td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8f9fa;">Số tiết:</th>
                                <td class="fw-bold text-danger">{{ $content->periods }} tiết</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-success mt-4 mb-5">
                <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>🎯 Các Yêu cầu cần đạt của Nội dung này</span>
                    <span class="badge bg-light text-success">{{ $content->objectives->count() }} yêu cầu</span>
                </div>
                <div class="card-body p-0">

                    <ul class="list-group list-group-flush">
                        @forelse($content->objectives as $index => $objective)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="me-3">
                                    <span class="fw-bold text-muted me-2">{{ $index + 1 }}.</span>
                                    {!! $objective->description !!}
                                </div>

                                <div class="d-flex align-items-center text-nowrap">
                                    <a href="{{ route('objectives.edit', $objective->id) }}"
                                        class="btn btn-sm btn-outline-warning me-2" title="Sửa">✏️</a>

                                    <form action="{{ route('objectives.destroy', $objective->id) }}" method="POST"
                                        class="m-0"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa yêu cầu cần đạt này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="Xóa">🗑️</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center py-4 text-muted fst-italic">
                                Nội dung này chưa có yêu cầu cần đạt nào. Hãy thêm ở bên dưới!
                            </li>
                        @endforelse
                    </ul>
                    <div class="mb-2">
                        <form action="{{ route('objectives.store', $content->id) }}" method="POST"
                            onsubmit="tinymce.triggerSave();">
                            @csrf
                            <div class="mb-2">
                                <textarea id="editor_description" name="description" placeholder="Nhập yêu cầu cần đạt mới..."></textarea>
                            </div>

                            <button class="btn btn-success fw-bold" type="submit">➕ Lưu Yêu cầu cần đạt</button>

                            @error('description')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mathModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">🧮 Trình soạn thảo Công thức Toán học</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3 p-2 bg-light border rounded">
                        <span class="fw-bold me-2 small">Gợi ý nhanh:</span>
                        <div class="mb-3 p-2 bg-light border rounded">
                        </div>

                        <div class="mb-3 p-2 border border-info rounded bg-white d-flex align-items-center gap-3">
                            <label class="form-label fw-bold text-info mb-0">Kiểu hiển thị:</label>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="mathDisplayMode" id="modeInline"
                                    value="inline" checked>
                                <label class="form-check-label" for="modeInline" title="Hiển thị cùng dòng với văn bản chữ">
                                    Trong dòng <code>\(\)</code>
                                </label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="mathDisplayMode" id="modeDisplay"
                                    value="display">
                                <label class="form-check-label" for="modeDisplay"
                                    title="Hiển thị to, rõ ràng ở giữa một dòng riêng biệt">
                                    Riêng dòng <code>\[\]</code>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">Nhập mã nguồn (LaTeX):</label>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math"
                                data-math="\frac{a}{b}">Phân số</button>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math"
                                data-math="\sqrt{x}">Căn bậc 2</button>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math"
                                data-math="x^{2}">Mũ
                                2</button>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math"
                                data-math="x_{1}">Chỉ
                                số dưới</button>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math"
                                data-math="\int_{a}^{b}">Tích phân</button>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math"
                                data-math="\sum_{i=1}^{n}">Tổng Sigma</button>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math"
                                data-math="\neq">Khác
                                (≠)</button>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math"
                                data-math="\approx">Xấp xỉ (≈)</button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">Nhập mã nguồn (LaTeX):</label>
                            <textarea id="mathLatexInput" class="form-control font-monospace" rows="3"
                                placeholder="Ví dụ: \frac{-b \pm \sqrt{\Delta}}{2a}"></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold text-success">Xem trước kết quả:</label>
                            <div id="mathPreview"
                                class="border border-success rounded p-3 bg-white d-flex align-items-center justify-content-center"
                                style="min-height: 80px; font-size: 1.5rem; overflow-x: auto;">
                                <span class="text-muted small">Công thức sẽ hiển thị ở đây...</span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="button" class="btn btn-success fw-bold" id="btnInsertMathToEditor">✅ Chèn vào nội
                            dung</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            window.MathJax = {
                tex: {
                    inlineMath: [
                        ['\\(', '\\)']
                    ],
                    displayMath: [
                        ['$$', '$$'],
                        ['\\[', '\\]']
                    ],
                    processEscapes: true
                },
                chtml: {
                    displayAlign: 'center'
                }
            };
        </script>

        <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
        <script id="MathJax-script" src="{{ asset('js/mathjax/tex-chtml.js') }}"></script>

        <script>
            // Hàng đợi MathJax chống đơ/giật
            let mathjaxPromise = Promise.resolve();

            // ----------------------------------------------------
            // KHỞI TẠO TINYMCE
            // ----------------------------------------------------
            tinymce.init({
                selector: '#editor_description', // Lưu ý: đảm bảo textarea của bạn có id này
                license_key: 'gpl',
                height: 250,
                menubar: false,
                plugins: 'lists link table',
                toolbar: 'undo redo | bold italic | bullist numlist | insert_math',

                setup: function(editor) {
                    // Thêm nút Toán học
                    editor.ui.registry.addButton('insert_math', {
                        text: '🧮 Chèn Toán',
                        tooltip: 'Mở bộ gõ công thức Toán học nâng cao',
                        onAction: function() {
                            document.getElementById('mathLatexInput').value = '';
                            document.getElementById('mathPreview').innerHTML =
                                '<span class="text-muted small">Công thức sẽ hiển thị ở đây...</span>';
                            var mathModal = new bootstrap.Modal(document.getElementById('mathModal'));
                            mathModal.show();
                            setTimeout(() => document.getElementById('mathLatexInput').focus(), 500);
                        }
                    });
                }
            });

            // ----------------------------------------------------
            // LOGIC XỬ LÝ MODAL TOÁN HỌC
            // ----------------------------------------------------
            document.addEventListener('DOMContentLoaded', function() {
                const mathInput = document.getElementById('mathLatexInput');
                const mathPreview = document.getElementById('mathPreview');
                const btnInsert = document.getElementById('btnInsertMathToEditor');

                // Tìm các radio button (nếu bạn đã thêm chọn inline/display ở trang show, nếu chưa có nó sẽ bỏ qua không bị lỗi)
                const radios = document.querySelectorAll('input[name="mathDisplayMode"]');

                function getDelimiters() {
                    const modeDisplay = document.getElementById('modeDisplay');
                    // Nếu tồn tại nút chọn Display và đang được check thì dùng \[\], ngược lại dùng \(\)
                    const isDisplay = modeDisplay ? modeDisplay.checked : false;
                    return isDisplay ? ['\\[', '\\]'] : ['\\(', '\\)'];
                }

                // HÀM CẬP NHẬT PREVIEW TRONG MODAL (ĐÃ FIX LỖI MATHJAX)
                function updateModalPreview() {
                    const latex = mathInput.value.trim();
                    if (latex === '') {
                        mathPreview.innerHTML = '<span class="text-muted small">Công thức sẽ hiển thị ở đây...</span>';
                        return;
                    }
                    const [start, end] = getDelimiters();
                    mathPreview.innerHTML = start + ' ' + latex + ' ' + end;

                    // Chạy lệnh dịch Toán học qua Hàng đợi Promise
                    if (typeof MathJax !== 'undefined' && MathJax.typesetPromise) {
                        mathjaxPromise = mathjaxPromise.then(() => {
                            MathJax.typesetClear([mathPreview]); // Xóa cache cũ
                            return MathJax.typesetPromise([mathPreview]); // Dịch mới
                        }).catch(err => console.error('Lỗi MathJax Modal:', err));
                    }
                }

                // Gắn sự kiện: Gõ phím hoặc đổi radio thì render lại
                if (mathInput) {
                    mathInput.addEventListener('input', updateModalPreview);
                }
                if (radios) {
                    radios.forEach(radio => radio.addEventListener('change', updateModalPreview));
                }

                // Bấm nút gợi ý nhanh
                document.querySelectorAll('.btn-insert-math').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const mathCode = this.getAttribute('data-math');
                        const startPos = mathInput.selectionStart;
                        const endPos = mathInput.selectionEnd;
                        mathInput.value = mathInput.value.substring(0, startPos) + mathCode + mathInput
                            .value.substring(endPos);

                        mathInput.dispatchEvent(new Event('input'));
                        mathInput.focus();
                    });
                });

                // Sự kiện: Bấm "Chèn vào nội dung"
                if (btnInsert) {
                    btnInsert.addEventListener('click', function() {
                        const latexCode = mathInput.value.trim();
                        if (latexCode !== '') {
                            const modeDisplay = document.getElementById('modeDisplay');
                            const isDisplay = modeDisplay ? modeDisplay.checked : false;
                            let mathHtml = '';

                            if (isDisplay) {
                                mathHtml = '<p class="text-center math-tex">\\[ ' + latexCode + ' \\]</p>';
                            } else {
                                mathHtml = '&nbsp;<span class="math-tex">\\( ' + latexCode +
                                ' \\)</span>&nbsp;';
                            }

                            tinymce.activeEditor.insertContent(mathHtml);
                        }
                        const modalInstance = bootstrap.Modal.getInstance(document.getElementById('mathModal'));
                        modalInstance.hide();
                    });
                }
            });
        </script>
    @endsection
