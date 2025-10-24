import React, {useEffect} from 'react';

const RecognizedImage = ({url, objects}) => {
    const canvasRef = React.useRef(null);

    useEffect(() => {
        const canvas = canvasRef.current;
        const ctx = canvas.getContext('2d');

        const image = new Image();
        image.crossOrigin = 'anonymous';
        image.src = url;

        image.onload = () => {
            canvas.height = image.naturalHeight;
            canvas.width = image.naturalWidth;

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(image, 0, 0);

            if(objects && objects.length > 0){
                objects.forEach(object => {
                    const {name, probability, bounding_box} = object;
                    const {x, y, width, height} = bounding_box

                    ctx.strokeStyle = "red";
                    ctx.lineWidth = 2;

                    ctx.strokeRect(x, y, width, height);

                    ctx.font = "18px Arial";
                    ctx.textBaseline = "bottom";

                    const label = `${name} (${Math.round(probability * 100)}%)`;

                    const textMetrics = ctx.measureText(label);
                    ctx.fillRect(x, y - 20, textMetrics.width + 10, 20);

                    ctx.fillStyle = "white";
                    ctx.fillText(label, x + 5, y - 2)
                })
            }
        }
    }, [url, objects]);
    return (
        <div className={"image"}>
            <canvas ref={canvasRef} style={{ maxWidth: '100%', height: 'auto', borderRadius: '5px' }}></canvas>
        </div>
    );
};

export default RecognizedImage;