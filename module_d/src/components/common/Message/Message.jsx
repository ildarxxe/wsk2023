import React, {useEffect, useState} from 'react';
import './Message.css'

const Message = ({showCursor, sender, text, type, url}) => {
    const [cursorVisible, setCursorVisible] = useState(true);

    useEffect(() => {
        if (!showCursor) return;

        const interval = setInterval(() => {
            setCursorVisible(v => !v);
        }, 500);

        return () => clearInterval(interval);
    }, [showCursor]);
    return (
        <div className={"message " + (sender ? "sender" : "recipient")}>
            {type === "text" ? (
                <p>{text}{showCursor && <span style={{ visibility: cursorVisible ? 'visible' : 'hidden' }}>|</span>}</p>
            ) : (
                <img src={url} alt="image" />
            )}
        </div>
    );
};

export default Message;