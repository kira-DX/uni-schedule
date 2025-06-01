<?php

use App\Services\YouTubeService;

Route::get('/youtube-upcoming-test', function (YouTubeService $youtubeService) {
    $channelId = 'UC0Lod2RRUlxiqLfXcfhfemA';  // 確認したいチャンネルIDに置き換えてください

    try {
        $upcomingStreams = $youtubeService->getUpcomingStreams($channelId);

        return response()->json([
            'status' => 'success',
            'data' => $upcomingStreams,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});
