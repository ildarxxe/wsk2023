import React, {useEffect} from 'react';
import './Input.css'

const Input = ({type, handleSubmit, value, setValue, disabled}) => {
    function clearInput() {
        setValue('');
    }

    function myHandleSubmit() {
        if(value !== '' && type === 'text') {
            handleSubmit();
            setValue('');
        } else if (value !== null && type === 'file') {
            handleSubmit()
            setValue(null);
        }
    }

    useEffect(() => {
        const button = document.querySelector('.send');
        if (button && type === "text") {
            button.disabled = disabled;
        }
    }, [disabled, type]);

    function handleFileChange(e) {
        setValue(e.target.files[0]);
    }

    return (
        type === "file" ? (
            <div className="input">
                <input type="file" accept="image/*" onChange={handleFileChange} name={"prompt"}/>
                <button
                    className={"send"}
                    type={"button"}
                    onClick={myHandleSubmit}>
                    >
                </button>
            </div>
        ) : (<div className={"input"}>
            <input type="text" value={value} onChange={(e) => setValue(e.target.value)} name={"prompt"}/>
            <span onClick={clearInput}><p>x</p></span>
            <button className={"send"} type={"button"} onClick={myHandleSubmit}>></button>
        </div>)
    );
};

export default Input;