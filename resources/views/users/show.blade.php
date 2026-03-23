@extends('layouts.app')

@section('title', 'Chi tiết người dùng')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Thông tin tài khoản: <span class="text-danger">{{ $user->name }}</span></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                ⬅ Trở về danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white fw-bold">
                    ⚠️ Khu vực nguy hiểm: Xác nhận xóa người dùng
                </div>
                <div class="card-body">
                    <p class="text-muted">Vui lòng kiểm tra kỹ thông tin trước khi thực hiện hành động xóa. Dữ liệu sau khi xóa sẽ không thể khôi phục.</p>
                    
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>Tên đăng nhập:</strong> {{ $user->userName }}</li>
                        <li class="list-group-item"><strong>Họ và tên:</strong> {{ $user->name }}</li>
                        <li class="list-group-item">
                            <strong>Quyền hạn:</strong> 
                            @if ($user->isAdmin)
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-secondary">Giáo viên</span>
                            @endif
                        </li>
                        <li class="list-group-item"><strong>Chức vụ:</strong> {{ $user->label ?? 'Không có' }}</li>
                        <li class="list-group-item"><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
                    </ul>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-light">Hủy bỏ</a>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có CHẮC CHẮN muốn xóa tài khoản {{ $user->userName }} này không? Hành động này không thể hoàn tác!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger fw-bold">
                                🗑️ Có, Xóa người dùng này
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection