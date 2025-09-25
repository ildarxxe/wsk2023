import './DreamWeaver.css'

import React from 'react';
import Input from "../../components/Input/Input";
import Message from "../../components/common/Message/Message";

const DreamWeaver = () => {
    const [messages, setMessages] = React.useState([]);
    const [value, setValue] = React.useState("");
    const [disabled, setDisabled] = React.useState(false);

    function handleNewChat() {
        window.location.reload()
    }

    function handleSubmit() {

    }

    return (
        <>
            <div className="new_chat">
                <span onClick={handleNewChat}>+</span>
                <p>New chat</p>
            </div>
            <div className="dialog">
                {messages.map((message, index) => (
                    <Message type={"text"} key={index} showCursor={false} sender={message.sender} text={message.text} />
                ))}
            </div>
            <div className={"dreamweaver-page"}>
                <Input type={"text"} disabled={disabled} value={value} setValue={setValue} handleSubmit={handleSubmit} />
            </div>
        </>
    );
};

export default DreamWeaver;