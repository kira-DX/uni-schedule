<?php

use App\Services\YouTubeService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    $file = public_path('react/index.html');
    return File::exists($file) ? Response::file($file) : abort(404);
})->where('any', '.*');