<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YoutubeController extends Controller
{
    // YouTube Data APIから特定チャンネルの動画一覧を取得する例
    public function getVideos()
    {
        $apiKey = env('GOOGLE_API_KEY');  // .envにYouTube APIキーを入れておく
        $channelId = 'UCNN0n29dfU-F3DQ4YvEenuQ'; // 対象のYouTubeチャンネルID

        $url = 'https://www.googleapis.com/youtube/v3/search';
        $response = Http::get($url, [
            'key' => $apiKey,
            'channelId' => $channelId,
            'part' => 'snippet',
            'order' => 'date',
            'maxResults' => 5,
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }
}