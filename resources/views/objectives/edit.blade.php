@extends('layouts.app')

@section('title', 'Sửa Yêu cầu cần đạt')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Chỉnh sửa Yêu cầu cần đạt</h1>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark fw-bold">
                    ✏️ Cập nhật nội dung
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('objectives.update', $objective->id) }}" method="POST">
                        @csrf 
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">👁️ Xem trước kết quả (Live Preview):</label>
                            <div id="globalPreview" class="border border-primary rounded p-3 bg-white shadow-sm" style="min-height: 100px; max-height: 400px; overflow-y: auto;">
                                <span class="text-muted small">Nội dung bạn soạn thảo sẽ hiển thị trực tiếp tại đây...</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="editor_description" class="form-label fw-bold">Nội dung Yêu cầu cần đạt <span class="text-danger">*</span></label>
                            <div class="mb-2">
                                <textarea id="editor_description" name="description" placeholder="Nhập yêu cầu cần đạt mới...">{{ old('description', $objective->description) }}</textarea>
                            </div>
                            @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('contents.show', $objective->content_id) }}" class="btn btn-light me-2 border shadow-sm">✖ Hủy bỏ</a>
                            <button type="submit" class="btn btn-warning fw-bold text-dark shadow-sm">💾 Lưu Thay đổi</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mathModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">🧮 Trình soạn thảo Công thức Toán học</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3 p-2 bg-light border rounded">
                        <span class="fw-bold me-2 small">Gợi ý nhanh:</span>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math" data-math="\frac{a}{b}">Phân số</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math" data-math="\sqrt{x}">Căn bậc 2</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math" data-math="x^{2}">Mũ 2</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math" data-math="x_{1}">Chỉ số dưới</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math" data-math="\int_{a}^{b}">Tích phân</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math" data-math="\sum_{i=1}^{n}">Tổng Sigma</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math" data-math="\neq">Khác (≠)</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-insert-math" data-math="\approx">Xấp xỉ (≈)</button>
                    </div>

                    <div class="mb-3 p-2 border border-info rounded bg-white d-flex align-items-center gap-3">
                        <label class="form-label fw-bold text-info mb-0">Kiểu hiển thị:</label>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="mathDisplayMode" id="modeInline" value="inline" checked>
                            <label class="form-check-label" for="modeInline" title="Hiển thị cùng dòng với văn bản chữ">
                                Trong dòng <code>\(\)</code>
                            </label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="mathDisplayMode" id="modeDisplay" value="display">
                            <label class="form-check-label" for="modeDisplay" title="Hiển thị to, rõ ràng ở giữa một dòng riêng biệt">
                                Riêng dòng <code>\[\]</code>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary">Nhập mã nguồn (LaTeX):</label>
                        <textarea id="mathLatexInput" class="form-control font-monospace" rows="3" placeholder="Ví dụ: \frac{-b \pm \sqrt{\Delta}}{2a}"></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-success">Xem trước công thức:</label>
                        <div id="mathPreview" class="border border-success rounded p-3 bg-white d-flex align-items-center justify-content-center" style="min-height: 80px; font-size: 1.5rem; overflow-x: auto;">
                            <span class="text-muted small">Công thức sẽ hiển thị ở đây...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-success fw-bold" id="btnInsertMathToEditor">✅ Chèn vào nội dung</button>
                </div>
            </div>
        </div>
    </div>

   <script>
        window.MathJax = {
            tex: {
                inlineMath: [['\\(', '\\)']],
                displayMath: [['$$', '$$'], ['\\[', '\\]']],
                processEscapes: true // Cho phép escape các ký tự đặc biệt
            },
            chtml: { displayAlign: 'center' }
        };
    </script>

    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script id="MathJax-script" src="{{ asset('js/mathjax/tex-chtml.js') }}"></script>

    <script>
        // Hàng đợi (Queue) cho MathJax để tránh lỗi đụng độ tiến trình khi gõ phím nhanh
        let mathjaxPromise = Promise.resolve();
        let previewTimeout = null;

        // Hàm cập nhật Live Preview Toàn cục
        function updateGlobalPreview(htmlContent) {
            const previewDiv = document.getElementById('globalPreview');
            
            if (!htmlContent || htmlContent.trim() === '') {
                previewDiv.innerHTML = '<span class="text-muted small">Nội dung bạn soạn thảo sẽ hiển thị trực tiếp tại đây...</span>';
                return;
            }
            
            // Đổ HTML từ TinyMCE vào khung Preview
            previewDiv.innerHTML = htmlContent;

            // Đợi 400ms sau khi ngừng gõ mới gọi MathJax
            clearTimeout(previewTimeout);
            previewTimeout = setTimeout(() => {
                if (typeof MathJax !== 'undefined' && MathJax.typesetPromise) {
                    
                    // Đưa lệnh dịch toán vào hàng đợi tuần tự
                    mathjaxPromise = mathjaxPromise.then(() => {
                        MathJax.typesetClear([previewDiv]); // Bắt MathJax quên cache cũ ở div này
                        return MathJax.typesetPromise([previewDiv]); // Bắt đầu dịch mới
                    }).catch((err) => {
                        console.error('Lỗi MathJax Live Preview:', err);
                    });
                    
                }
            }, 400);
        }

       // Khởi tạo TinyMCE
        tinymce.init({
            selector: '#editor_description',
            license_key: 'gpl',
            height: 250,
            menubar: false,
            plugins: 'lists link table',
            toolbar: 'undo redo | bold italic | bullist numlist | insert_math',

            setup: function(editor) {
                // Nút mở hộp thoại Toán
                editor.ui.registry.addButton('insert_math', {
                    text: '🧮 Chèn Toán',
                    tooltip: 'Mở bộ gõ công thức Toán học nâng cao',
                    onAction: function() {
                        document.getElementById('mathLatexInput').value = '';
                        document.getElementById('mathPreview').innerHTML = '<span class="text-muted small">Công thức sẽ hiển thị ở đây...</span>';
                        var mathModal = new bootstrap.Modal(document.getElementById('mathModal'));
                        mathModal.show();
                        setTimeout(() => document.getElementById('mathLatexInput').focus(), 500);
                    }
                });

                // ĐÃ SỬA: Bỏ keyup, change. Chỉ cập nhật khi load trang (init) hoặc click chuột ra ngoài (blur)
                editor.on('init blur', function() {
                    updateGlobalPreview(editor.getContent());
                });
            }
        });

        // Logic xử lý cho Hộp thoại Toán học (Modal)
        document.addEventListener('DOMContentLoaded', function() {
            const mathInput = document.getElementById('mathLatexInput');
            const mathPreview = document.getElementById('mathPreview');
            const btnInsert = document.getElementById('btnInsertMathToEditor');
            const radios = document.querySelectorAll('input[name="mathDisplayMode"]');

            function getDelimiters() {
                const isDisplay = document.getElementById('modeDisplay').checked;
                return isDisplay ? ['\\[', '\\]'] : ['\\(', '\\)'];
            }

            function updateModalPreview() {
                const latex = mathInput.value.trim();
                if (latex === '') {
                    mathPreview.innerHTML = '<span class="text-muted small">Công thức sẽ hiển thị ở đây...</span>';
                    return;
                }
                const [start, end] = getDelimiters();
                mathPreview.innerHTML = start + ' ' + latex + ' ' + end;
                
                if (typeof MathJax !== 'undefined' && MathJax.typesetPromise) {
                    mathjaxPromise = mathjaxPromise.then(() => {
                        MathJax.typesetClear([mathPreview]);
                        return MathJax.typesetPromise([mathPreview]);
                    }).catch(err => console.error(err));
                }
            }

            mathInput.addEventListener('input', updateModalPreview);
            radios.forEach(radio => radio.addEventListener('change', updateModalPreview));

            document.querySelectorAll('.btn-insert-math').forEach(btn => {
                btn.addEventListener('click', function() {
                    const mathCode = this.getAttribute('data-math');
                    const startPos = mathInput.selectionStart;
                    const endPos = mathInput.selectionEnd;
                    mathInput.value = mathInput.value.substring(0, startPos) + mathCode + mathInput.value.substring(endPos);
                    
                    mathInput.dispatchEvent(new Event('input')); 
                    mathInput.focus();
                });
            });

            // Sự kiện: Bấm "Chèn vào nội dung"
            btnInsert.addEventListener('click', function() {
                const latexCode = mathInput.value.trim();
                if (latexCode !== '') {
                    const isDisplay = document.getElementById('modeDisplay').checked;
                    let mathHtml = '';

                    if (isDisplay) {
                        mathHtml = '<p class="text-center math-tex">\\[ ' + latexCode + ' \\]</p>';
                    } else {
                        mathHtml = '&nbsp;<span class="math-tex">\\( ' + latexCode + ' \\)</span>&nbsp;';
                    }

                    // Chèn vào TinyMCE
                    tinymce.activeEditor.insertContent(mathHtml);

                    // ĐÃ THÊM: Ép khung Xem trước tổng cập nhật ngay lập tức sau khi chèn xong công thức
                    updateGlobalPreview(tinymce.activeEditor.getContent());
                }
                
                // Tắt Modal
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('mathModal'));
                modalInstance.hide();
            });
        });
    </script>
@endsection