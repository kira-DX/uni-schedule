import React, { useEffect, useState } from 'react';

function App() {
  const [articles, setArticles] = useState([]);

  useEffect(() => {
    fetch('/api/articles')
      .then((res) => res.json())
      .then((data) => setArticles(data))
      .catch((err) => console.error('API取得エラー:', err));
  }, []);

  return (
    <div>
      <h1>記事一覧</h1>
      <ul>
        {articles.map((article) => (
          <li key={article.id}>
            <h2>{article.title}</h2>
            <p>{article.body}</p>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default App;