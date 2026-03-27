<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // 1. THÊM DÒNG NÀY Ở ĐÂY

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 2. THÊM DÒNG NÀY VÀO TRONG HÀM BOOT
        Paginator::useBootstrapFive();
    }
}
