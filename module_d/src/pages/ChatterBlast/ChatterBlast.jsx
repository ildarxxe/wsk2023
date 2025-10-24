import React from 'react';

const ChatterBlast = ({setErrors}) => {
    const [messages, setMessages] = React.useState([]);
    const [question, setQuestion] = React.useState("");
    const [chatID, setChatID] = React.useState(null);
    const [isProcessing, setIsProcessing] = React.useState(false);

    function animateTyping(text, mID) {
        let currentAnswer = "";
        let i = 0;

        function typeNextChar() {
            if(i < text.length) {
                const delay = Math.floor(Math.random() * 18) + 2
                currentAnswer += text.charAt(i);
                i++
                setMessages((prevMessages) => {
                    return prevMessages.map((msg) => {
                        if (msg.id === mID) {
                            return {...msg, answer: currentAnswer}
                        }
                        return msg;
                    })
                });
                setTimeout(typeNextChar, delay);
            } else {
                setMessages((prevMessages) => {
                    return prevMessages.map((msg) => {
                        if (msg.id === mID) {
                            return {...msg, isTyping: false}
                        }
                        return msg;
                    })
                });
                setIsProcessing(false)
            }
        }

        typeNextChar()
    }

    async function sendMessage() {
        if(question.length > 0) {
            const questionClone = question;
            setQuestion("")
            const newUserMessage = {
                id: Date.now(),
                question: questionClone,
                answer: "",
                isTyping: true,
            };
            setMessages((prevMessages) => [...prevMessages, newUserMessage]);

            let response;
            if (messages.length === 0) {
                response = await fetch("http://127.0.0.1:8000/api/chat/conversation", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-API-TOKEN": localStorage.getItem("token"),
                    },
                    body: JSON.stringify({
                        "prompt": questionClone,
                    })
                }).catch(err => {
                    setErrors((prevState) => [...prevState, "Услуга временно недоступна."]);
                });
            } else {
                response = await fetch(`http://127.0.0.1:8000/api/chat/conversation/${chatID}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-API-TOKEN": localStorage.getItem("token"),
                    },
                    body: JSON.stringify({
                        "prompt": questionClone,
                    })
                }).catch(err => {
                    setErrors((prevState) => [...prevState, "Услуга временно недоступна."]);
                });
            }

            if(!response.ok) {
                switch(response.status) {
                    case 400:
                        setErrors((prevState) => [...prevState, "Неверный формат данных."]);
                        break;
                    case 401:
                        setErrors((prevState) => [...prevState, "Токен API недействителен."]);
                        break;
                    case 403:
                        setErrors((prevState) => [...prevState, "Квота выставления счетов исчерпана."]);
                        break;
                    case 503:
                        setErrors((prevState) => [...prevState, "Услуга временно недоступна."]);
                        break;
                    default:
                        break;
                }
                return
            } else {
                const answer = await response.json()
                setChatID(answer.id || answer.conversation_id)
                setIsProcessing(true)
                animateTyping(answer.response, newUserMessage.id);
            }
        }
    }

    function newChat() {
        window.location.reload();
    }

    return (
        <div className="chatter-blast vh-100 d-flex flex-column flex-column">
            <div className="new-chat w-100 text-center mt-2 mb-2">
                <button className="btn btn-outline-primary" onClick={newChat}>NEW CHAT</button>
            </div>

            <div className="messages pb-5">
                <div className="container">
                    {messages.length > 0 && messages.map(message => (
                        <React.Fragment key={message.id}>
                            <div className="jumbotron ml-auto mb-1 mt-1 w-50" key={`q-${message.id}`}>
                                <p className={"mb-0"}>{message.question}</p>
                            </div>

                            <div className="jumbotron mb-1 mt-1 w-50" key={`a-${message.id}`}>
                                <p className={"mb-0"}>
                                    {message.answer}
                                    {message.isTyping && <span className="typing-cursor">|</span>}
                                </p>
                            </div>
                        </React.Fragment>
                    ))}
                </div>
            </div>

            <div className="input w-100 d-flex justify-content-center align-items-center fixed-bottom">
                <div className="input-group position-fixed mb-lg-5 w-50">
                    <input value={question} onChange={(e) => setQuestion(e.target.value)} type="text" name="" className={"form-control"} placeholder={"Введите ваш запрос..."} id=""/>
                    <div className="input-group-append">
                        <button disabled={isProcessing} className={"btn-send btn btn-primary"} onClick={sendMessage}>Отправить</button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ChatterBlast;