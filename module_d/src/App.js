import './App.css';
import Home from "./pages/Home/Home"
import {BrowserRouter, Route, Routes} from "react-router";
import ChatterBlast from "./pages/ChatterBlast/ChatterBlast";
import MindReader from "./pages/MindReader/MindReader";
import DreamWeaver from "./pages/DreamWeaver/DreamWeaver";
import {useEffect, useState} from "react";
import ErrorAlert from "./components/ErrorAlert/ErrorAlert";

function App() {
    const [errors, setErrors] = useState([]);
    useEffect(() => {
        localStorage.setItem("token", "test-api-token-123")
    }, []);
    return (
        <div className="app">
            <div className="errors_block">
                {errors.length > 0 && errors.map(err => (
                    <ErrorAlert text={err} />
                ))}
            </div>
            <BrowserRouter>
                <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/chatter-blast" element={<ChatterBlast setErrors={setErrors} />} />
                    <Route path="/dream-weaver" element={<DreamWeaver setErrors={setErrors} />} />
                    <Route path="/mind-reader" element={<MindReader setErrors={setErrors} />} />
                </Routes>
            </BrowserRouter>
        </div>
    );
}

export default App;
