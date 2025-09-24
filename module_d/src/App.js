import './App.css';
import Home from "./pages/Home/Home"
import {BrowserRouter, Route, Routes} from "react-router";

function App() {
    return (
        <div className="app">
            <BrowserRouter>
                <Routes>
                    <Route path="/" element={<Home />} />
                </Routes>
            </BrowserRouter>
        </div>
    );
}

export default App;
