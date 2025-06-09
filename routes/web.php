<?php

use App\Services\YouTubeService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/members', function () {
    return file_get_contents(public_path('react/index.html'));
});