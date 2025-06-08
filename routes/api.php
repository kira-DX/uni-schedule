<?php

use App\Models\Article;
use Illuminate\Http\Request;

Route::get('/articles', function () {
    return Article::all();
});