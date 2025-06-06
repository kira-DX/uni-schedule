import { useEffect, useState } from "react";

export default function Clock() {
  const [now, setNow] = useState(new Date());

  useEffect(() => {
    const timer = setInterval(() => setNow(new Date()), 1000);
    return () => clearInterval(timer); // クリーンアップ
  }, []);

  return (
    <div className="text-center mt-10">
      <h1 className="text-xl">🕒 現在時刻</h1>
      <p className="text-2xl mt-2">{now.toLocaleTimeString()}</p>
    </div>
  );
}