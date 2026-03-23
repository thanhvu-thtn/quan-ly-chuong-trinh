@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Tổng quan chương trình Vật Lý</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Chào mừng đến với hệ thống quản lý</h5>
                    <p class="card-text">
                        Tại đây, bạn có thể dễ dàng tra cứu và viết lại phân phối chương trình Vật Lý THPT và Vật Lý THPT Chuyên theo chương trình khung của Bộ Giáo dục và Đào tạo Việt Nam.
                    </p>
                    <a href="#" class="btn btn-primary">Xem chương trình khung ngay</a>
                </div>
            </div>
        </div>
    </div>
@endsection