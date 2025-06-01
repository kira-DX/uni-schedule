<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>ライブ配信予定一覧</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .video-list { display: flex; flex-wrap: wrap; gap: 1rem; }
        .video-item { border: 1px solid #ccc; padding: 1rem; width: 300px; }
        .thumbnail img { max-width: 100%; height: auto; }
        .title { font-weight: bold; margin-top: 0.5rem; }
        .scheduled { color: #555; margin-top: 0.5rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <h1>今後のライブ配信予定</h1>

    @if(count($videos) === 0)
        <p>現在、予定されているライブはありません。</p>
    @else
        <div class="video-list">
            @foreach ($videos as $video)
                <div class="video-item">
                    <div class="thumbnail">
                        <a href="https://www.youtube.com/watch?v={{ $video['videoId'] }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ $video['thumbnail'] }}" alt="サムネイル">
                        </a>
                    </div>
                    <div class="title">{{ $video['title'] }}</div>
                    <div class="scheduled">配信予定日時: {{ \Carbon\Carbon::parse($video['scheduledAt'])->locale('ja')->isoFormat('YYYY年M月D日 HH:mm') }}</div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>