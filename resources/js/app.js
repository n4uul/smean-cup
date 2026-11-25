import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function initializeBlueprintGrid() {
    const canvas = document.getElementById('blueprint-grid-canvas');
    const context = canvas instanceof HTMLCanvasElement ? canvas.getContext('2d') : null;

    if (!canvas || !context || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const body = document.body;
    const spacing = 72;
    const radius = 190;
    const sampleStep = 8;
    const pointer = {
        currentX: -1000,
        currentY: -1000,
        targetX: -1000,
        targetY: -1000,
        strength: 0,
    };
    let width = 0;
    let height = 0;
    let pixelRatio = 1;
    let animationFrame = 0;

    body.classList.add('blueprint-grid-canvas-active');

    function resizeCanvas() {
        pixelRatio = Math.min(window.devicePixelRatio || 1, 2);
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = Math.round(width * pixelRatio);
        canvas.height = Math.round(height * pixelRatio);
        context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
        draw(performance.now());
    }

    function warpPoint(x, y, time) {
        const deltaX = x - pointer.currentX;
        const deltaY = y - pointer.currentY;
        const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

        if (pointer.strength < 0.01 || distance > radius * 2) {
            return [x, y];
        }

        const influence = Math.exp(-distance / radius) * pointer.strength;
        const ripple = Math.sin(distance * 0.055 - time * 0.004) * influence * 7;
        const magneticPull = influence * 3;
        const displacement = (ripple + magneticPull) / Math.max(distance, 1);

        return [
            x + deltaX * displacement,
            y + deltaY * displacement,
        ];
    }

    function drawGridLine(startX, startY, endX, endY, vertical, time) {
        const length = vertical ? height : width;
        const start = vertical ? startY : startX;
        const lineX = startX;
        const lineY = startY;

        context.beginPath();

        for (let offset = start; offset <= length; offset += sampleStep) {
            const x = vertical ? lineX : offset;
            const y = vertical ? offset : lineY;
            const [warpedX, warpedY] = warpPoint(x, y, time);

            if (offset === start) {
                context.moveTo(warpedX, warpedY);
            } else {
                context.lineTo(warpedX, warpedY);
            }
        }

        context.lineTo(endX, endY);
        context.stroke();
    }

    function draw(timestamp) {
        animationFrame = 0;
        const time = timestamp || performance.now();

        pointer.currentX += (pointer.targetX - pointer.currentX) * 0.18;
        pointer.currentY += (pointer.targetY - pointer.currentY) * 0.18;

        context.clearRect(0, 0, width, height);
        context.strokeStyle = 'rgba(17, 19, 21, 0.055)';
        context.lineWidth = 1;

        for (let x = 0; x <= width; x += spacing) {
            drawGridLine(x, 0, x, height, true, time);
        }

        for (let y = 0; y <= height; y += spacing) {
            drawGridLine(0, y, width, y, false, time);
        }

        pointer.strength *= 0.94;
        if (pointer.strength >= 0.01) {
            animationFrame = window.requestAnimationFrame(draw);
        }
    }

    function updatePointer(event) {
        pointer.targetX = event.clientX;
        pointer.targetY = event.clientY;

        if (pointer.currentX < 0) {
            pointer.currentX = pointer.targetX;
            pointer.currentY = pointer.targetY;
        }

        pointer.strength = 1;

        if (!animationFrame) {
            animationFrame = window.requestAnimationFrame(draw);
        }
    }

    window.addEventListener('pointermove', updatePointer, { passive: true });
    window.addEventListener('pointerdown', updatePointer, { passive: true });
    window.addEventListener('resize', resizeCanvas, { passive: true });
    resizeCanvas();
}

initializeBlueprintGrid();
