import React from 'react';
import '../styles/Schedule.scss';

const scheduleData = {
  'June 10': [
    { time: '19:00', title: 'Time', thumbnail: 'live1.png' },
    { time: '21:00', title: 'Titalo', thumbnail: 'gamepad.png' },
    { time: '22:30', title: 'Titalo', thumbnail: 'avatar.png' },
    { time: '23:00', title: 'Titole', thumbnail: 'image.png' },
  ],
  'June 11': [
    { time: '18:00', title: 'Stream', thumbnail: 'stream.png' },
    { time: '20:00', title: 'Titalo', thumbnail: 'live2.png' },
    { time: '21:30', title: 'Titalo', thumbnail: 'avatar.png' },
    { time: '23:00', title: 'Titole', thumbnail: 'image.png' },
  ],
  'June 12': [
    { time: '13:30', title: 'LIVE!', thumbnail: 'live1.png' },
    { time: '16:00', title: 'LIVE', thumbnail: 'gamepad.png' },
    { time: '20:00', title: 'Titalo', thumbnail: 'avatar.png' },
    { time: '21:00', title: 'Titole', thumbnail: 'image.png' },
  ]
};

const Schedule = () => {
  return (
    <div className="schedule-wrapper">
      <header className="schedule-header">
        <h1><span role="img" aria-label="earth">🌏</span> UniVIRTUAL <span className="light">Schedule (unofficial)</span></h1>
      </header>

      <div className="schedule-container">
        {Object.entries(scheduleData).map(([date, items]) => (
          <section key={date} className="schedule-day">
            <h2>{date}</h2>
            <div className="schedule-list">
              {items.map((item, index) => (
                <div key={index} className="schedule-item">
                  <img src={`assets/${item.thumbnail}`} alt={item.title} />
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
