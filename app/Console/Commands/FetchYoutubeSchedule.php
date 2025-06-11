<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Member;
use App\Models\LiveStreamingData;

class FetchYoutubeSchedule extends Command
{
    protected $signature = 'fetch:youtube-schedule';
    protected $description = 'Fetch upcoming and recent live videos from YouTube for registered members';

    public function handle()
    {
        $apiKey = config('services.youtube.api_key'); // .env に YOUTUBE_API_KEY を設定

        // ✅ 3日前以前（UTC）のデータを削除
        $cutoffDate = Carbon::now('UTC')->subDays(3)->endOfDay();
        $deletedCount = LiveStreamingData::where('scheduled_time', '<', $cutoffDate)->delete();
        $this->info("Old data deleted (before {$cutoffDate->toDateTimeString()} UTC): {$deletedCount} records");

        $members = Member::all();

        $startTime = Carbon::now()->subDays(2)->startOfDay()->toRfc3339String();
        $endTime = Carbon::tomorrow()->endOfDay()->toRfc3339String();

        foreach ($members as $member) {
            $channelId = $member->channel_id;

            $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $channelId,
                'type' => 'video',
                'order' => 'date',
                'publishedAfter' => $startTime,
                'publishedBefore' => $endTime,
                'key' => $apiKey,
                'maxResults' => 50,
            ]);

            if ($response->failed()) {
                $this->error("apikey = $apiKey");
                $this->error("Failed to fetch data for channel: $channelId");
                continue;
            }

            $videos = $response->json('items');

            foreach ($videos as $video) {
                $videoId = $video['id']['videoId'];
                $detailsResponse = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                    'part' => 'snippet,liveStreamingDetails',
                    'id' => $videoId,
                    'key' => $apiKey,
                ]);

                if ($detailsResponse->failed()) {
                    $this->error("Failed to fetch details for video: $videoId");
                    continue;
                }

                $details = $detailsResponse->json('items')[0] ?? null;
                if (!$details) continue;

                // scheduled_timeはライブ予約時間 or 公開日時で設定する
                $scheduledTime = $details['liveStreamingDetails']['scheduledStartTime'] ?? $details['snippet']['publishedAt'] ?? null;
                if (isset($details['liveStreamingDetails']['actualStartTime'])) {
                    $liveStatus = 'live';
                } elseif (isset($details['liveStreamingDetails']['scheduledStartTime'])) {
                    $liveStatus = 'upcoming';
                } else {
                    $liveStatus = 'none'; // ライブ配信ではない通常動画 or ショート
                }
                $type = $this->detectVideoType($details);

                LiveStreamingData::updateOrCreate(
                    ['video_id' => $videoId],
                    [
                        'channel_id' => $channelId,
                        'title' => $details['snippet']['title'],
                        'thumbnail_url' => $details['snippet']['thumbnails']['high']['url'] ?? '',
                        'scheduled_time' => $scheduledTime,
                        'live_status' => $liveStatus,
                        'type' => $type,
                    ]
                );
            }
        }

        $this->info('YouTube schedules fetched and saved successfully.');
    }

    private function detectVideoType(array $details): string
    {
        $title = strtolower($details['snippet']['title']);

        if (str_contains($title, 'short') || $details['snippet']['categoryId'] == 'shorts') {
            return 'short';
        }

        if (isset($details['liveStreamingDetails'])) {
            return 'live';
        }

        return 'video';
    }
}