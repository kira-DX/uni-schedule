import React, { useEffect, useState } from 'react';

function App() {
  const [articles, setArticles] = useState([]);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/articles')
      .then((res) => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
      })
      .then((data) => setArticles(data))
      .catch((e) => setError(e.message));
  }, []);

  if (error) return <div>API取得エラー: {error}</div>;

  return (
    <div>
      <h1>記事一覧</h1>
      <ul>
        {articles.map((a) => (
          <li key={a.id}>
            <h2>{a.title}</h2>
            <p>{a.body}</p>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default App;