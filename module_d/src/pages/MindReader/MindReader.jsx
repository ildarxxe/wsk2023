import React from 'react';
import RecognizedImage from "../../components/RecognizedImage/RecognizedImage";

const MindReader = ({setErrors}) => {
    const [messages, setMessages] = React.useState([]);
    const [file, setFile] = React.useState(null);
    const [isProcessing, setIsProcessing] = React.useState(false);

    async function sendMessage() {
        if(file && !isProcessing) {
            const formData = new FormData();
            formData.append('image', file);

            const origin_image_url = URL.createObjectURL(file);

            setFile(null);
            setIsProcessing(true);

            const res = await fetch("http://localhost:8000/api/imagerecognition/recognize", {
                method: 'POST',
                headers: {
                    "X-API-TOKEN": localStorage.getItem("token"),
                },
                body: formData,
            }).catch(err => {
                setErrors((prevState) => [...prevState, "Услуга временно недоступна."]);
            });

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
                setIsProcessing(false)
                return
            } else {
                const data = await res.json();

                const newObject = {
                    id: Date.now(),
                    origin_image_url: origin_image_url,
                    objects: data.objects
                }
                setMessages((prevMessages) => [...prevMessages, newObject]);
                setIsProcessing(false);
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
                                <img src={message.origin_image_url} alt="recognized image"/>
                            </div>
                            <div className="jumbotron mb-1 mt-1">
                                <RecognizedImage objects={message.objects} url={message.origin_image_url} />
                                <p>Найдено объектов: {message.objects.length}</p>
                            </div>
                        </React.Fragment>
                    ))}
                </div>
            </div>

            <div className="input w-100 d-flex justify-content-center align-items-center fixed-bottom">
                <div className="input-group position-fixed mb-lg-5 w-50">
                    <input onChange={(e) => setFile(e.target.files[0])} type="file" name="" className={"form-control"} placeholder={"Введите ваш запрос..."} id=""/>
                    <div className="input-group-append">
                        <button disabled={isProcessing} className={"btn-send btn btn-primary"} onClick={sendMessage}>Распознать</button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default MindReader;