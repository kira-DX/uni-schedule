<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\LiveStreamingData;

Route::get('/live-streams', function () {
    // UTCからJST（+9時間）に変換してソートするクエリビルダー例
    // MySQLのCONVERT_TZ関数を利用
    $liveStreams = LiveStreamingData::select(
        'id',
        'title',
        'video_id',
        'scheduled_time',
        \DB::raw("CONVERT_TZ(scheduled_time, '+00:00', '+09:00') as scheduled_time_jst"),
        'thumbnail_url',
        'live_status',
        'type'
    )
    ->orderBy('scheduled_time_jst', 'asc')
    ->get();

    return response()->json($liveStreams);
});