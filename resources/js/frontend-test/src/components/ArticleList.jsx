import React, { useEffect, useState } from 'react';

const ArticleList = () => {
  const [articles, setArticles] = useState([]);

  useEffect(() => {
    fetch('/api/articles')
      .then((res) => res.json())
      .then((data) => setArticles(data))
      .catch((err) => console.error('API取得エラー:', err));
  }, []);

  return (
    <div>
      <h2>記事一覧（Laravel APIから取得）</h2>
      <ul>
        {articles.map((a) => (
          <li key={a.id}>
            <h3>{a.title}</h3>
            <p>{a.content}</p>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default ArticleList;