<?php

use App\Services\YouTubeService;
use Illuminate\Support\Facades\Route;

Route::get('/live-schedule', function (YouTubeService $youtube) {
    // チャンネルIDは適宜差し替えてください（例: 公式のチャンネルID）
    $channelId = 'UCT16mw3e7Lm8DZzjnRfealg';

    $videos = $youtube->getUpcomingStreams($channelId);

    return view('live_schedule', ['videos' => $videos]);
});