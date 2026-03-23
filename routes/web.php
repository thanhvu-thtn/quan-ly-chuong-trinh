<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TopicTypeController;
use App\Http\Controllers\TopicController; 

Route::get('/', function () {
    return view('home.home');
});


//Nhóm AuthController
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
    // Các route khác như đăng ký, đăng xuất có thể thêm vào đây
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});


// Route về user
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/create', [UserController::class, 'store'])->name('users.store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/edit/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

});

// Route về Loại chuyên đề
Route::prefix('topic-types')->group(function () {
    Route::get('/', [TopicTypeController::class, 'index'])->name('topic-types.index');
    // Các route khác như tạo, sửa, xóa có thể thêm vào đây
    Route::get('/create', [TopicTypeController::class, 'create'])->name('topic-types.create');
    Route::post('/create', [TopicTypeController::class, 'store'])->name('topic-types.store');
    Route::get('/edit/{id}', [TopicTypeController::class, 'edit'])->name('topic-types.edit');
    Route::put('/edit/{id}', [TopicTypeController::class, 'update'])->name('topic-types.update');
    Route::get('/delete/{id}', [TopicTypeController::class, 'delete'])->name('topic-types.delete');
    Route::delete('/delete/{id}', [TopicTypeController::class, 'destroy'])->name('topic-types.destroy');
});

// Route về Chuyên đề
Route::prefix('topics')->group(function () {
    Route::get('/', [TopicController::class, 'index'])->name('topics.index');
    // Các route khác như tạo, sửa, xóa có thể thêm vào đây
    Route::get('/create', [TopicController::class, 'create'])->name('topics.create');
    Route::post('/create', [TopicController::class, 'store'])->name('topics.store');
    Route::get('/edit/{id}', [TopicController::class, 'edit'])->name('topics.edit');
    Route::put('/edit/{id}', [TopicController::class, 'update'])->name('topics.update');
    Route::get('/delete/{id}', [TopicController::class, 'delete'])->name('topics.delete');
    Route::delete('/delete/{id}', [TopicController::class, 'destroy'])->name('topics.destroy');
});

// Nhóm yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    
    // ... route trang chủ và users ...
    Route::resource('users', UserController::class);

    // Thêm route quản lý Loại chuyên đề vào đây
    Route::resource('topic-types', TopicTypeController::class);

    // Thêm 2 route xuất file NẰM TRÊN resource
    Route::get('topics/export/pdf', [TopicController::class, 'exportPdf'])->name('topics.export.pdf');
    Route::get('topics/export/word', [TopicController::class, 'exportWord'])->name('topics.export.word');
    // Xuất file chi tiết một chuyên đề
    Route::get('topics/{id}/export-detail/pdf', [TopicController::class, 'exportDetailPdf'])->name('topics.exportDetail.pdf');
    Route::get('topics/{id}/export-detail/word', [TopicController::class, 'exportDetailWord'])->name('topics.exportDetail.word');
    Route::resource('topics', TopicController::class);

});

