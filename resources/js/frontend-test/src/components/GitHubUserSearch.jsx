import { useState } from "react";

export default function GitHubUserSearch() {
  const [username, setUsername] = useState("");
  const [data, setData] = useState(null);

  const fetchUser = async () => {
    const res = await fetch(`https://api.github.com/users/${username}`);
    const json = await res.json();
    setData(json);
  };

  return (
    <div className="max-w-md mx-auto mt-10 text-center">
      <input
        type="text"
        placeholder="kira-DX"
        value={username}
        onChange={(e) => setUsername(e.target.value)}
        className="border p-2 mr-2"
      />
      <button onClick={fetchUser} className="bg-green-500 text-white px-4 py-2 rounded">
        検索
      </button>

      {data && (
        <div className="mt-4">
          <img src={data.avatar_url} alt="avatar" className="w-24 h-24 rounded-full mx-auto" />
          <h2 className="text-lg mt-2">{data.name || "No name found"}</h2>
          <p>{data.bio}</p>
        </div>
      )}
    </div>
  );
}