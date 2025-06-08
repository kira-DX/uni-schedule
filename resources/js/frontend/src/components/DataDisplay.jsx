// src/components/DataDisplay.jsx
import React from 'react';

export default function DataDisplay({ loading, data }) {
  if (loading) {
    return <p>データ取得中...</p>;
  }
  return <p>取得データ: {data}</p>;
}