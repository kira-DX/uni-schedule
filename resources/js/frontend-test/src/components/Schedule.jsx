import React, { useEffect, useState, useRef } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
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
  const scrollRef = useRef(null);
  const todayKey = formatDateLabel(new Date()); // 今日の日付キー（自動スクロール用）

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
            videoId: item.video_id,
            channel_name: item.channel_name
          });
        });

        setScheduleData(grouped);

        // スクロールをトリガー（遅延実行で描画後に発火）
        setTimeout(() => {
          scrollRef.current?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
      })
      .catch((err) => console.error('Failed to fetch schedule:', err));
  }, []);

  return (
    <div className="schedule-wrapper container">
      <header className="schedule-header text-center mb-4">
        <h1><span role="img" aria-label="earth">🌏💫</span> Uni<span className="yellow">VIRTUAL</span><br className="d-sm-none" /><span className="light">Schedule(非公式)</span></h1>
      </header>

      <div className="schedule-container">
        {Object.entries(scheduleData).map(([date, items]) => (
          <section
            key={date}
            className="schedule-day mb-5"
            ref={date === todayKey ? scrollRef : null} // 今日のセクションだけrefをセット
          >
            <h2 className="text-center py-2 px-3">{date}</h2>
            <div className="row justify-content-start g-4">
              {items.map((item, index) => (
                <div key={index} className="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                  <div className="schedule-item h-100 d-flex flex-column">
                    <a
                      href={`https://www.youtube.com/watch?v=${item.videoId}`}
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      <div className="thumbnail-wrapper">
                        <img src={item.thumbnail} alt={item.title} className="img-fluid rounded" />
                      </div>
                    </a>
                    <span className="time mt-2">{item.time}</span>
                    <span className="title">{item.channel_name}</span>
                  </div>
                </div>
              ))}
            </div>
          </section>
        ))}
      </div>

      <footer className="text-center mt-5 mb-4">
        <a href="/privacy-policy.html" target="_blank" rel="noopener noreferrer" className="text-secondary text-decoration-none">
          プライバシーポリシー
        </a>
      </footer>
    </div>
  );
};

export default Schedule;