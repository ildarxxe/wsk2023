import React from 'react';

const DreamWeaver = ({setErrors}) => {
    const [messages, setMessages] = React.useState([]);
    const [question, setQuestion] = React.useState("");
    const [isProcessing, setIsProcessing] = React.useState(false);

    async function fetchImage(jobID) {
        try {
            const res = await fetch(`http://localhost:8000/api/imagegeneration/status/${jobID}`, {
                method: "GET",
                headers: {
                    "X-API-TOKEN": localStorage.getItem("token"),
                }
            })
            if(res.ok) {
                return await res.json();
            } else {
                switch(res.status) {
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
            }
        } catch (error) {
            setErrors((prevState) => [...prevState, "Услуга временно недоступна."]);
            return {error: error};
        }
    }

    async function sendMessage() {
        if(question.length > 0) {
            const questionClone = question;
            setQuestion("")

            const newObject = {
                id: Date.now(),
                question: questionClone,
                url: "",
                progress: 0,
                isProcess: true,
            }
            setMessages((prevMessages) => [...prevMessages, newObject]);

            if(!isProcessing) {
                const res = await fetch(`http://localhost:8000/api/imagegeneration/generate`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-API-TOKEN": localStorage.getItem("token"),
                    },
                    body: JSON.stringify({
                        text_prompt: questionClone,
                    })
                }).catch(err => {
                    setErrors((prevState) => [...prevState, "Услуга временно недоступна."]);
                })
                if(!res.ok) {
                    switch(res.status) {
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
                    const generateData = await res.json();
                    setIsProcessing(true)

                    const intervalID = setInterval(async () => {
                        const data = await fetchImage(generateData.job_id)
                        if(!data.error) {
                            if (data.progress === 100) {
                                clearInterval(intervalID)
                                setMessages((prevMessages) => {
                                    return prevMessages.map(msg => {
                                        if(msg.id === newObject.id) {
                                            return {...msg, url: data.image_url, isProcess: false, progress: 100}
                                        }
                                        return msg
                                    })
                                });
                                setIsProcessing(false)
                            } else {
                                setMessages((prevMessages) => {
                                    return prevMessages.map(msg => {
                                        if(msg.id === newObject.id) {
                                            return {...msg, progress: data.progress}
                                        }
                                        return msg
                                    })
                                });
                            }
                        } else {
                            clearInterval(intervalID)
                            console.log(data.error)
                        }
                    }, 2000)
                }
            }
        }
    }
    return (
        <div className="dream-weaver vh-100 d-flex flex-column flex-column">
            <div className="messages pb-5">
                <div className="container">
                    {messages.map((message) => (
                        <React.Fragment key={message.id}>
                            <div className="jumbotron mb-1 mt-1 ml-auto">
                                <p className={"p-0"}>{message.question}</p>
                            </div>
                            <div className="jumbotron mb-1 mt-1">
                                {message.url === "" ? (
                                    <div className={"d-flex flex-column align-items-center"}>
                                        <div className={"spinner-border"} role={"status"}>
                                            <span className={'sr-only'}>{message.progress}</span>
                                        </div>
                                        <strong>{message.progress}%</strong>
                                    </div>
                                ) : (
                                    <>
                                        <img src={message?.url} alt="generated image" />
                                        <div className="buttons d-flex align-items-center mt-1">
                                            <button className="btn btn-primary">Сохранить</button>
                                            <button className="btn btn-primary">Качественное изображение</button>
                                            <button className="btn btn-primary circle">+</button>
                                            <button className="btn btn-primary circle">-</button>
                                        </div>
                                    </>
                                )}
                            </div>
                        </React.Fragment>
                    ))}
                </div>
            </div>

            <div className="input w-100 d-flex justify-content-center align-items-center fixed-bottom">
                <div className="input-group position-fixed mb-lg-5 w-50">
                    <input value={question} onChange={(e) => setQuestion(e.target.value)} type="text" name="" className={"form-control"} placeholder={"Введите ваш запрос..."} id=""/>
                    <div className="input-group-append">
                        <button disabled={isProcessing} className={"btn-send btn btn-primary"} onClick={sendMessage}>Создать</button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DreamWeaver;