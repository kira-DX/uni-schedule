<?php

use App\Services\YouTubeService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// / → React本体（既存のビルド済みSPA）
Route::get('/', function () {
    return file_get_contents(public_path('react/index.html'));
});

// /test → 別のReactビルド or 画面
Route::get('/test', function () {
    return file_get_contents(public_path('react-test/index.html'));
});