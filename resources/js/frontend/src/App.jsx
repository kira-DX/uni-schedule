import React, { useEffect, useState } from 'react';

export default function App() {
  const [articles, setArticles] = useState([]);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/articles')
      .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
      })
      .then(data => setArticles(data))
      .catch(err => setError(err.message));
  }, []);

  if (error) return <div>API取得エラー: {error}</div>;

  return (
    <div>
      <h1>Articles List</h1>
      <ul>
        {articles.map(a => (
          <li key={a.id}>
            <strong>{a.title}</strong><br />
            {a.body}
          </li>
        ))}
      </ul>
    </div>
  );
}