<?php

use App\Http\Controllers\YoutubeController;

Route::get('/youtube/videos', [YoutubeController::class, 'getVideos']);