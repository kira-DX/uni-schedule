<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\LiveStreamingData;
use Carbon\Carbon;

Route::get('/live-streams', function () {
    // 現在のJST時間を基準に前日〜翌日の範囲を取得
    $start = Carbon::now('Asia/Tokyo')->subDay()->startOfDay()->setTimezone('UTC');
    $end = Carbon::now('Asia/Tokyo')->addDay()->endOfDay()->setTimezone('UTC');

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
    ->whereBetween('scheduled_time', [$start, $end])
    ->orderBy('scheduled_time', 'asc') // UTCで並べ替え
    ->get();

    return response()->json($liveStreams);
});