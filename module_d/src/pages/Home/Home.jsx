import React from 'react';

const Home = () => {
    return (
        <div className={"home"}>
            <nav className="navbar bg-light navbar-expand-lg navbar-light">
                <a className={"nav-link"} href="/chatter-blast">Chatter Blast</a>
                <a className={"nav-link"} href="/dream-weaver">Dream Weaver</a>
                <a className={"nav-link"} href="/mind-reader">Mind Reader</a>
            </nav>
        </div>
    );
};

export default Home;