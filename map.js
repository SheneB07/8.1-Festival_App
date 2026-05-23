const mapInner = document.getElementById("map-inner");

let scale = 1;
let posX = 0;
let posY = 0;

let isDragging = false;
let startX, startY;

// DRAG
mapInner.addEventListener("mousedown", (e) => {
    isDragging = true;
    startX = e.clientX - posX;
    startY = e.clientY - posY;
});

window.addEventListener("mouseup", () => {
    isDragging = false;
});

window.addEventListener("mousemove", (e) => {
    if (!isDragging) return;

    posX = e.clientX - startX;
    posY = e.clientY - startY;

    updateTransform();
});

// ZOOM
document.getElementById("map-box").addEventListener("wheel", (e) => {
    e.preventDefault();

    scale += e.deltaY * -0.001;
    scale = Math.min(Math.max(0.5, scale), 3);

    updateTransform();
});

function updateTransform() {
    mapInner.style.transform =
        `translate(${posX}px, ${posY}px) scale(${scale})`;

    updateMarkers();
}

const markers = document.querySelectorAll(".marker");

function updateMarkers() {
    markers.forEach(marker => {
        const x = parseFloat(marker.dataset.x);
        const y = parseFloat(marker.dataset.y);

        const screenX = x * scale + posX;
        const screenY = y * scale + posY;

        marker.style.left = screenX + "px";
        marker.style.top = screenY + "px";
    });
}

updateMarkers();