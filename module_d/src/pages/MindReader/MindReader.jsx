import Input from "../../components/Input/Input";
import React, { useState } from "react";
import ImageWithDetection from "../../components/common/ImageWithDetection/ImageWithDetection";
import './MindReader.css';

const MindReader = () => {
    const [file, setFile] = useState(null);
    const [messages, setMessages] = useState([]);

    function handleNewChat() {
        window.location.reload();
    }

    function handleSubmit() {
        if (file) {
            const url = URL.createObjectURL(file);
            const detectedObjects = imitateObjectDetection();

            setMessages((prevMessages) => [
                ...prevMessages,
                { url: url, detectedObjects: detectedObjects }
            ]);

            setFile(null);
        }
    }

    const imitateObjectDetection = () => {
        const imgWidth = 500;
        const imgHeight = 350;

        const newDetectedObjects = [];
        const numObjects = Math.floor(Math.random() * 5) + 3;

        for (let i = 0; i < numObjects; i++) {
            const x = Math.random() * (imgWidth * 0.7);
            const y = Math.random() * (imgHeight * 0.7);
            const width = Math.random() * (imgWidth - x) * 0.3 + 30;
            const height = Math.random() * (imgHeight - y) * 0.3 + 30;

            newDetectedObjects.push({ x, y, width, height });
        }
        return newDetectedObjects;
    };

    return (
        <>
            <div className="new_chat">
                <span onClick={handleNewChat}>+</span>
                <p>New chat</p>
            </div>
            <div className="dialog">
                {messages && messages.map((message, index) => (
                    <div key={index} className="message-container">
                        <ImageWithDetection
                            imageUrl={message.url}
                            detectedObjects={message.detectedObjects}
                        />
                    </div>
                ))}
            </div>
            <div className={"mindreader-page"}>
                <Input type={"file"} value={file} setValue={setFile} handleSubmit={handleSubmit} />
            </div>
        </>
    );
};

export default MindReader;
