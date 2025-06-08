<?php

use Illuminate\Support\Facades\Route;

Route::get('/articles', function () {
    return [
        ['id' => 1, 'title' => 'Hello World', 'body' => 'This is a test article.'],
        ['id' => 2, 'title' => 'Another Article', 'body' => 'More sample content here.'],
    ];
});