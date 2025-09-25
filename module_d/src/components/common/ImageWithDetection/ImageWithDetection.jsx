import React, { useRef, useEffect } from 'react';

const ImageWithDetection = ({ imageUrl, detectedObjects }) => {
    const imageRef = useRef(null);
    const canvasRef = useRef(null);

    useEffect(() => {
        if (!imageUrl || !imageRef.current || !canvasRef.current) {
            return;
        }

        const canvas = canvasRef.current;
        const ctx = canvas.getContext('2d');
        const img = imageRef.current;

        img.onload = () => {
            canvas.width = img.width;
            canvas.height = img.height;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            detectedObjects.forEach(obj => {
                ctx.strokeStyle = 'red';
                ctx.lineWidth = 2;
                ctx.strokeRect(obj.x, obj.y, obj.width, obj.height);
            });
        };

        if (img.complete) {
            img.onload();
        }

    }, [imageUrl, detectedObjects]);

    return (
        <div style={{ position: 'relative', display: 'inline-block', margin: '20px', padding: '10px', border: '1px solid black', borderRadius: '10px' }}>
            <p>Найдено {detectedObjects.length} объектов</p>
            <img
                ref={imageRef}
                src={imageUrl}
                alt="Uploaded"
                style={{ maxWidth: '100%', maxHeight: '400px', objectFit: 'contain' }}
            />
            <canvas
                ref={canvasRef}
                style={{
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    pointerEvents: 'none'
                }}
            />
        </div>
    );
};

export default ImageWithDetection;
