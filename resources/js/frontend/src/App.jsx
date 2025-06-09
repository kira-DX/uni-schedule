import React from 'react';
import ReactDOM from 'react-dom/client';
import MemberList from './components/MemberList';

const App = () => <MemberList />;

export default App;

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<App />);