import React, { useEffect, useState } from 'react';

const LiveStreamList = () => {
  const [liveStreams, setLiveStreams] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/live-streams')
      .then((res) => res.json())
      .then((data) => {
        setLiveStreams(data);
        setLoading(false);
      })
      .catch((error) => {
        console.error('Error fetching live streams:', error);
        setLoading(false);
      });
  }, []);

  if (loading) return <p>Loading...</p>;

  return (
    <div>
      <h2>YouTube配信スケジュール一覧</h2>
      <ul>
        {liveStreams.map(({ id, title, scheduled_time_jst, video_id, thumbnail_url }) => (
          <li key={id} style={{ marginBottom: '20px' }}>
            <a
              href={`https://www.youtube.com/watch?v=${video_id}`}
              target="_blank"
              rel="noopener noreferrer"
            >
              <img
                src={thumbnail_url}
                alt={title}
                style={{ width: '120px', height: '90px', marginRight: '10px', verticalAlign: 'middle' }}
              />
              <strong>{title}</strong>
            </a>
            <div>
              配信時間: {new Date(scheduled_time_jst).toLocaleString('ja-JP', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Tokyo',
              })}
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default LiveStreamList;