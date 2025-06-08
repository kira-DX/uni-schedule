<?php

use Illuminate\Support\Facades\Route;

Route::get('/articles', function () {
    return response()->json([
        ['id' => 1, 'title' => 'React連携テスト', 'content' => 'これはLaravelから取得したデータです'],
        ['id' => 2, 'title' => '2件目の記事', 'content' => 'これもAPI経由で取得しています'],
    ]);
});