import { useState } from "react";

export default function Counter() {
  const [count, setCount] = useState(0);

  return (
    <div className="text-center mt-10">
      <h1 className="text-2xl mb-4">🧮 カウント：{count}</h1>
      <button
        onClick={() => setCount(count + 1)}
        className="px-4 py-2 bg-blue-500 text-white rounded-lg"
      >
        カウントアップ
      </button>
    </div>
  );
}