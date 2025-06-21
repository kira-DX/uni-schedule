import React, { useEffect, useState } from 'react';
import '../styles/Schedule.scss';

const formatDateLabel = (dateStr) => {
  const date = new Date(dateStr);
  const weekday = ['日', '月', '火', '水', '木', '金', '土'];
  const month = date.getMonth() + 1;
  const day = date.getDate();
  const week = weekday[date.getDay()];
  return `${month}/${day}(${week})`;
};

const Schedule = () => {
  const [scheduleData, setScheduleData] = useState({});

  useEffect(() => {
    fetch('/api/live-streams')
      .then((res) => res.json())
      .then((data) => {
        const grouped = {};
        data.forEach((item) => {
          const dateKey = formatDateLabel(item.scheduled_time_jst);
          const time = new Date(item.scheduled_time_jst).toLocaleTimeString('ja-JP', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
            timeZone: 'Asia/Tokyo',
          });

          if (!grouped[dateKey]) grouped[dateKey] = [];
          grouped[dateKey].push({
            time,
            title: item.title,
            thumbnail: item.thumbnail_url,
            videoId: item.video_id
          });
        });
        setScheduleData(grouped);
      })
      .catch((err) => console.error('Failed to fetch schedule:', err));
  }, []);

  return (
    <div className="schedule-wrapper">
      <header className="schedule-header">
        <h1><span role="img" aria-label="earth">🌏</span> UniVIRTUAL <span className="light">Schedule</span></h1>
      </header>

      <div className="schedule-container">
        {Object.entries(scheduleData).map(([date, items]) => (
          <section key={date} className="schedule-day">
            <h2>{date}</h2>
            <div className="schedule-list">
              {items.map((item, index) => (
                <div key={index} className="schedule-item">
                  <a href={`https://www.youtube.com/watch?v=${item.videoId}`} target="_blank" rel="noopener noreferrer">
                    <img src={item.thumbnail} alt={item.title} />
                  </a>
                  <span className="time">{item.time}</span>
                  <span className="title">{item.title}</span>
                </div>
              ))}
            </div>
          </section>
        ))}
      </div>
    </div>
  );
};

export default Schedule;