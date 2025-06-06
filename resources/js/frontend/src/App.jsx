import Counter from "./components/Counter";
import Clock from "./components/Clock";
import GitHubUserSearch from "./components/GitHubUserSearch";

function App() {
  return (
    <div className="p-4">
      <h1 className="text-3xl font-bold mb-6 text-center">🚀 React デモ</h1>
      <Counter />
      <Clock />
      <GitHubUserSearch />
    </div>
  );
}

export default App;