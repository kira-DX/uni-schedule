// src/App.jsx
import React, { useState, useEffect } from 'react';
import CounterButton from './components/CounterButton';
import DataDisplay from './components/DataDisplay';

export default function App() {
  const [count, setCount] = useState(0);
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleClick = () => setCount(count + 1);

  useEffect(() => {
    setLoading(true);
    const timer = setTimeout(() => {
      setData(`現在のカウントは ${count} です`);
      setLoading(false);
    }, 1000);
    return () => clearTimeout(timer);
  }, [count]);

  return (
    <div style={{ padding: 20 }}>
      <h1>React機能サンプル</h1>
      <CounterButton onClick={handleClick} />
      <p>カウント: {count}</p>
      <DataDisplay loading={loading} data={data} />
    </div>
  );
}