import React, { useEffect, useState } from 'react';

const MemberList = () => {
    const [members, setMembers] = useState([]);

    useEffect(() => {
        fetch('/api/members')
            .then(res => res.json())
            .then(data => setMembers(data))
            .catch(err => console.error(err));
    }, []);

    return (
        <div style={{ padding: '2rem', fontFamily: 'sans-serif' }}>
            <h1>メンバー一覧</h1>
            <ul style={{ listStyle: 'none', padding: 0 }}>
                {members.map((member) => (
                    <li key={member.channel_id} style={{ marginBottom: '0.5rem' }}>
                        <a
                            href={`https://www.youtube.com/channel/${member.channel_id}`}
                            target="_blank"
                            rel="noopener noreferrer"
                            style={{ textDecoration: 'none', color: '#007bff' }}
                        >
                            {member.name}
                        </a>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default MemberList;