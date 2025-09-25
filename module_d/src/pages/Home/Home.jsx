import React from 'react';
import './Home.css'
import {Link} from "react-router";

const Home = () => {
    return (
        <>
            <header>
                <nav>
                    <Link to={"/chatterblast"}>ChatterBlast</Link>
                    <Link to={"/dreamweaver"}>Dream Weaver</Link>
                    <Link to={"/mindreader"}>MindReader</Link>
                </nav>
            </header>
            <div className={"home_page"}>
                <h1>Добро пожаловать на гавную страницу!</h1>
            </div>
        </>
    );
};

export default Home;