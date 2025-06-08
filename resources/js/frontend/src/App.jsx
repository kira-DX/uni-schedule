import React, { useState, useEffect } from 'react';

export default function App() {
  const [count, setCount] = useState(0);
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(false);

  // ボタンクリックでカウントアップ
  const handleClick = () => {
    setCount(count + 1);
  };

  // countが変わるたびに1秒遅延でフェイクAPIからデータ取得
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
      <button onClick={handleClick}>カウントアップ</button>
      <p>カウント: {count}</p>
      {loading ? <p>データ取得中...</p> : <p>取得データ: {data}</p>}
    </div>
  );
}