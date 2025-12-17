<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Member;
use App\Models\LiveStreamingData;

class FetchYoutubeSchedule extends Command
{
    protected $signature = 'fetch:youtube-schedule';
    protected $description = 'Fetch upcoming and recent live videos from YouTube for registered members (batched)';

    public function handle()
    {
        $apiKey = config('services.youtube.api_key'); // .env に YOUTUBE_API_KEY を設定
        if (!$apiKey) {
            $this->error('YouTube API key is not configured.');
            return Command::FAILURE;
        }

        // ✅ 3日前以前（UTC）のデータを削除
        $cutoffDate = Carbon::now('UTC')->subDays(3)->endOfDay();
        $deletedCount = LiveStreamingData::where('scheduled_time', '<', $cutoffDate)->delete();
        $this->info("Old data deleted (before {$cutoffDate->toDateTimeString()} UTC): {$deletedCount} records");

        $members = Member::all();

        $startTime = Carbon::now('UTC')->subDays(2)->startOfDay()->toRfc3339String();
        $endTime   = Carbon::now('UTC')->addDay()->endOfDay()->toRfc3339String();

        foreach ($members as $member) {
            $channelId = $member->channel_id;

            // videoId を取得
            $searchResponse = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $channelId,
                'type' => 'video',
                'order' => 'date',
                'publishedAfter' => $startTime,
                'publishedBefore' => $endTime,
                'key' => $apiKey,
                'maxResults' => 50,
            ]);

            if ($searchResponse->failed()) {
                $this->error("Failed to fetch search results for channel: $channelId");
                Log::warning('YouTube search failed', [
                    'channel_id' => $channelId,
                    'status' => $searchResponse->status(),
                    'body' => $searchResponse->body(),
                ]);
                continue;
            }

            $items = $searchResponse->json('items') ?? [];
            if (empty($items)) {
                continue;
            }

            $videoIds = [];
            foreach ($items as $item) {
                $vid = $item['id']['videoId'] ?? null;
                if ($vid) $videoIds[] = $vid;
            }
            $videoIds = array_values(array_unique($videoIds));

            if (empty($videoIds)) {
                continue;
            }

            // videos.list を最大50件まとめて取得
            foreach (array_chunk($videoIds, 50) as $idChunk) {
                $detailsResponse = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                    'part' => 'snippet,liveStreamingDetails',
                    'id' => implode(',', $idChunk),
                    'key' => $apiKey,
                    'maxResults' => 50,
                ]);

                if ($detailsResponse->failed()) {
                    $this->error('Failed to fetch video details (batched).');
                    Log::warning('YouTube videos.list failed', [
                        'channel_id' => $channelId,
                        'status' => $detailsResponse->status(),
                        'body' => $detailsResponse->body(),
                        'ids' => $idChunk,
                    ]);
                    continue;
                }

                $detailsItems = $detailsResponse->json('items') ?? [];
                if (empty($detailsItems)) {
                    continue;
                }

                foreach ($detailsItems as $details) {
                    $videoId = $details['id'] ?? null;
                    if (!$videoId) continue;

                    // 不正チェック
                    $actualChannelId = $details['snippet']['channelId'] ?? null;
                    if (!$actualChannelId) {
                        $this->warn("No channelId in snippet for video: $videoId");
                        Log::warning('No channelId in snippet', ['video_id' => $videoId]);
                        continue;
                    }

                    if ($actualChannelId !== $channelId) {
                        // 正規チャンネルを装って別チャンネル動画が混入した場合はここで弾く
                        $msg = "ChannelId mismatch! member=$channelId actual=$actualChannelId video=$videoId";
                        $this->warn($msg);
                        Log::warning('ChannelId mismatch', [
                            'member_channel_id' => $channelId,
                            'actual_channel_id' => $actualChannelId,
                            'video_id' => $videoId,
                        ]);
                        continue;
                    }

                    // scheduled_time は予約開始 or 公開日時
                    $scheduledTime = $details['liveStreamingDetails']['scheduledStartTime']
                        ?? $details['snippet']['publishedAt']
                        ?? null;

                    if (isset($details['liveStreamingDetails']['actualStartTime'])) {
                        $liveStatus = 'live';
                    } elseif (isset($details['liveStreamingDetails']['scheduledStartTime'])) {
                        $liveStatus = 'upcoming';
                    } else {
                        $liveStatus = 'none';
                    }

                    $type = $this->detectVideoType($details);

                    LiveStreamingData::updateOrCreate(
                        ['video_id' => $videoId],
                        [
                            'channel_id' => $actualChannelId,
                            'title' => $details['snippet']['title'] ?? '',
                            'thumbnail_url' => $details['snippet']['thumbnails']['high']['url'] ?? '',
                            'scheduled_time' => $scheduledTime,
                            'live_status' => $liveStatus,
                            'type' => $type,
                        ]
                    );
                }
            }
        }

        $this->info('YouTube schedules fetched and saved successfully.');
        return Command::SUCCESS;
    }

    private function detectVideoType(array $details): string
    {
        $title = strtolower($details['snippet']['title'] ?? '');

        if (str_contains($title, 'short') || str_contains($title, '#shorts')) {
            return 'short';
        }

        if (isset($details['liveStreamingDetails'])) {
            return 'live';
        }

        return 'video';
    }
}
