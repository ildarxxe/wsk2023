import React from 'react';

const ErrorAlert = ({text}) => {
    return (
        <div className="alert alert-danger" role="alert">
            {text}
        </div>
    );
};

export default ErrorAlert;