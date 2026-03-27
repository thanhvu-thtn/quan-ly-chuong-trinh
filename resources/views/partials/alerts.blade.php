{{-- resources/views/partials/alerts.blade.php --}}

<div id="app-alerts" style="position: fixed; top: 20px; right: 20px; z-index: 1050; min-width: 300px; max-width: 450px;">
    
    {{-- Thông báo Thành công --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
            ✅ <strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Thông báo Lỗi chung --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
            ❌ <strong>Lỗi!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Cảnh báo --}}
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
            ⚠️ <strong>Chú ý!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Lỗi Validation từ Form Request --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
            <strong>❌ Vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

</div>

{{-- Script tự động ẩn thông báo sau 3.5 giây --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            let alerts = document.querySelectorAll('#app-alerts .alert');
            alerts.forEach(function(alertElement) {
                // Sử dụng API của Bootstrap 5 để đóng alert mượt mà
                let bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            });
        }, 3500); // 3500 mili-giây = 3.5 giây
    });
</script>