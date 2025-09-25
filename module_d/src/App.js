import './App.css';
import Home from "./pages/Home/Home"
import {BrowserRouter, Route, Routes} from "react-router";
import ChatterBlast from "./pages/ChatterBlast/ChatterBlast";
import MindReader from "./pages/MindReader/MindReader";
import DreamWeaver from "./pages/DreamWeaver/DreamWeaver";

function App() {
    return (
        <div className="app">
            <BrowserRouter>
                <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/chatterblast" element={<ChatterBlast />} />
                    <Route path="/dreamweaver" element={<DreamWeaver />} />
                    <Route path="/mindreader" element={<MindReader />} />
                </Routes>
            </BrowserRouter>
        </div>
    );
}

export default App;
