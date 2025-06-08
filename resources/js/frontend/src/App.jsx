import React, { useEffect, useState } from 'react';

function App() {
  const [videos, setVideos] = useState([]);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/youtube/videos')
      .then((res) => {
        if (!res.ok) throw new Error('API取得エラー: ' + res.status);
        return res.json();
      })
      .then((data) => {
        if (data.items) {
          setVideos(data.items);
        } else {
          setError('動画データがありません');
        }
      })
      .catch((err) => setError(err.message));
  }, []);

  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      <h1>YouTube チャンネル動画一覧</h1>
      <ul>
        {videos.map((video) => (
          <li key={video.id.videoId}>
            <h3>{video.snippet.title}</h3>
            <img src={video.snippet.thumbnails.medium.url} alt={video.snippet.title} />
          </li>
        ))}
      </ul>
    </div>
  );
}

export default App;