<?php

namespace App\Services;

use Google_Client;
use Google_Service_YouTube;

class YouTubeService
{
    protected $client;
    protected $youtube;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Uni Schedule');
        $this->client->setDeveloperKey(env('GOOGLE_API_KEY'));

        $this->youtube = new Google_Service_YouTube($this->client);
    }

    /**
     * 指定したチャンネルの「今後の配信予定」を取得
     */
    public function getUpcomingStreams($channelId, $maxResults = 10)
    {
        $params = [
            'channelId'    => $channelId,
            'eventType'    => 'upcoming',
            'type'         => 'video',
            'maxResults'   => $maxResults,
            'order'        => 'date',
        ];

        $response = $this->youtube->search->listSearch('snippet', $params);

        $videos = [];

        foreach ($response['items'] as $item) {
            $videos[] = [
                'title'       => $item['snippet']['title'],
                'videoId'     => $item['id']['videoId'],
                'thumbnail'   => $item['snippet']['thumbnails']['medium']['url'],
                'scheduledAt' => $item['snippet']['publishedAt'], // 実際はliveStreamingDetailsの取得が必要
            ];
        }

        return $videos;
    }
}