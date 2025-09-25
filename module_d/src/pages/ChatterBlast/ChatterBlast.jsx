import './ChatterBlast.css'

import React, {useState} from 'react';
import Input from "../../components/Input/Input";
import Message from "../../components/common/Message/Message";

const ChatterBlast = () => {
    const [messages, setMessages] = React.useState([]);
    const [value, setValue] = React.useState("");
    const [disabled, setDisabled] = React.useState(false);

    const [typingAnswer, setTypingAnswer] = useState("");
    const [isTyping, setIsTyping] = useState(false);

    function startTyping(answer) {
        setIsTyping(true);
        setTypingAnswer("");
        let index = 0;

        const typeChar = () => {
            if (index < answer.length) {
                setTypingAnswer((prev) => prev + answer[index]);
                index++
                const delay = Math.floor(Math.random() * (20 - 2 + 1)) + 2;
                setTimeout(typeChar, delay);
            } else {
                setIsTyping(false);
                setDisabled(false);

                setMessages((prev) => [...prev, { sender: false, text: answer }]);
                setTypingAnswer("");
            }
        }

        typeChar();
    }

    function handleNewChat() {
        window.location.reload()
    }

    function handleSubmit() {
        if (value.trim() === "") return;
        setMessages((prev) => [...prev, { sender: true, text: value }]);
        setDisabled(true);

        startTyping("answer from AI adhiawd awduiawdnaw dufadbwc q wbw b u qhdwuqwd qwdh wu answer from AI adhiawd awduiawdnaw dufadbwc q wbw b u qhdwuqwd qwdh wu answer from AI adhiawd awduiawdnaw dufadbwc q wbw b u qhdwuqwd qwdh wu answer from AI adhiawd awduiawdnaw dufadbwc q wbw b u qhdwuqwd qwdh wu answer from AI adhiawd awduiawdnaw dufadbwc q wbw b u qhdwuqwd qwdh wu answer from AI adhiawd awduiawdnaw dufadbwc q wbw b u qhdwuqwd qwdh wu");
        setValue("");
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
                {isTyping && (
                    <Message type={"text"} sender={false} text={typingAnswer} showCursor={true} />
                )}
            </div>
            <div className={"chatterblast-page"}>
                <Input type={"text"} disabled={disabled} value={value} setValue={setValue} handleSubmit={handleSubmit} />
            </div>
        </>
    );
};

export default ChatterBlast;