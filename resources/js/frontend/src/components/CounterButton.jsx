// src/components/CounterButton.jsx
import React from 'react';

export default function CounterButton({ onClick }) {
  return <button onClick={onClick}>カウントアップ</button>;
}