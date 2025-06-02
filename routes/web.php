<?php

use App\Services\YouTubeService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// APIや他のルートがあれば上に定義

// SPA用のcatch-allルート（最後に追加）
Route::get('/{any}', function () {
    $indexPath = public_path('react/index.html');
    if (File::exists($indexPath)) {
        return Response::make(file_get_contents($indexPath), 200)
            ->header("Content-Type", "text/html");
    } else {
        abort(404, 'React index.html not found.');
    }
})->where('any', '.*');