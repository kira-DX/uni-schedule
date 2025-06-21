<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\LiveStreamingData;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Route::get('/live-streams', function () {
    // JST基準で前日0:00〜翌日23:59をUTCに変換
    $start = Carbon::now('Asia/Tokyo')->subDay()->startOfDay()->setTimezone('UTC');
    $end = Carbon::now('Asia/Tokyo')->addDay()->endOfDay()->setTimezone('UTC');

    // JOINしてmembers.nameを取得
    $liveStreams = DB::table('live_streaming_data')
        ->select(
            'live_streaming_data.id',
            'live_streaming_data.title',
            'live_streaming_data.video_id',
            'live_streaming_data.channel_id',
            'live_streaming_data.scheduled_time',
            DB::raw("CONVERT_TZ(live_streaming_data.scheduled_time, '+00:00', '+09:00') as scheduled_time_jst"),
            'live_streaming_data.thumbnail_url',
            'live_streaming_data.live_status',
            'live_streaming_data.type',
            'members.name as channel_name'
        )
        ->leftJoin('members', 'live_streaming_data.channel_id', '=', 'members.channel_id')
        ->whereBetween('live_streaming_data.scheduled_time', [$start, $end])
        ->orderBy('live_streaming_data.scheduled_time', 'asc')
        ->get();

    return response()->json($liveStreams);
});