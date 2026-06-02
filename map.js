document.addEventListener('DOMContentLoaded', () => {
    const mapInner = document.getElementById("map-inner");

    let scale = 1;
    let posX = 0;
    let posY = 0;

    let isDragging = false;
    let startX, startY;

    mapInner.addEventListener("pointerdown", (e) => {
        isDragging = true;

        startX = e.clientX - posX;
        startY = e.clientY - posY;

        mapInner.setPointerCapture(e.pointerId);
    });

    mapInner.addEventListener("pointermove", (e) => {
        if (!isDragging) return;

        posX = e.clientX - startX;
        posY = e.clientY - startY;

        updateTransform();
    });

    mapInner.addEventListener("pointerup", () => {
        isDragging = false;
    });

    mapInner.addEventListener("pointercancel", () => {
        isDragging = false;
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

    // --- GPS / locate me ---
    const markerLayer = document.getElementById("marker-layer");
    const mapImg = document.getElementById("map");
    const mapBox = document.getElementById("map-box");

    const gpsMarker = document.createElement("div");
    gpsMarker.id = "gps-marker";
    gpsMarker.style.display = "none";
    markerLayer.appendChild(gpsMarker);

    let watchId = null;
    let gpsPos = { lat: null, lon: null, accuracy: 0, imgX: null, imgY: null };

    function latLonToImageXY(lat, lon) {
        const d = mapInner.dataset;
        if (!d.lat1 || !d.lon1 || !d.lat2 || !d.lon2) return null;

        const lat1 = parseFloat(d.lat1);
        const lon1 = parseFloat(d.lon1);
        const lat2 = parseFloat(d.lat2);
        const lon2 = parseFloat(d.lon2);

        if (isNaN(lat1) || isNaN(lon1) || isNaN(lat2) || isNaN(lon2)) return null;

        // fraction across the image
        const fx = (lon - lon1) / (lon2 - lon1);
        const fy = (lat - lat1) / (lat2 - lat1);

        const imgW = mapImg.naturalWidth || mapImg.width;
        const imgH = mapImg.naturalHeight || mapImg.height;

        const x = fx * imgW;
        const y = (1 - fy) * imgH; // y origin assumed top

        return { x, y };
    }

    function updateGpsMarker() {
        if (!gpsPos.lat) return;

        const xy = latLonToImageXY(gpsPos.lat, gpsPos.lon);

        if (xy) {
            gpsPos.imgX = xy.x;
            gpsPos.imgY = xy.y;

            const screenX = gpsPos.imgX * scale + posX;
            const screenY = gpsPos.imgY * scale + posY;

            gpsMarker.style.left = screenX + "px";
            gpsMarker.style.top = screenY + "px";
            gpsMarker.style.display = "block";
            gpsMarker.setAttribute("data-accuracy", gpsPos.accuracy || 0);
        } else {
            // no mapping provided: show a small dot near the top-right of the map
            const offsetRight = 40; // px from right edge
            const offsetTop = 24; // px from top edge
            gpsMarker.style.left = (mapBox.clientWidth - offsetRight) + "px";
            gpsMarker.style.top = offsetTop + "px";
            gpsMarker.style.display = "block";
            gpsMarker.textContent = "";
            gpsMarker.title = `${gpsPos.lat.toFixed(5)}, ${gpsPos.lon.toFixed(5)}`;
            gpsMarker.setAttribute("data-accuracy", gpsPos.accuracy || 0);
        }
    }

    function startGeolocation() {
        if (!navigator.geolocation) {
            alert("Geolocation is not supported by your browser.");
            return;
        }

        if (watchId) return;

        watchId = navigator.geolocation.watchPosition(pos => {
            gpsPos.lat = pos.coords.latitude;
            gpsPos.lon = pos.coords.longitude;
            gpsPos.accuracy = pos.coords.accuracy;

            updateGpsMarker();
        }, err => {
            console.warn(err);
        }, { enableHighAccuracy: true, maximumAge: 1000, timeout: 10000 });

        const btn = document.getElementById("locate-btn");
        if (btn) btn.textContent = "Stop locating";
    }

    function stopGeolocation() {
        if (watchId) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        gpsMarker.style.display = "none";
        const btn = document.getElementById("locate-btn");
        if (btn) btn.textContent = "Locate me";
    }

    const locateBtn = document.getElementById("locate-btn");
    if (locateBtn) {
        locateBtn.addEventListener("click", () => {
            if (!watchId) {
                startGeolocation();
            } else {
                stopGeolocation();
            }
        });
    }

    // keep gps marker updated when map moves/zooms
    const originalUpdateTransform = updateTransform;
    function wrappedUpdateTransform() {
        originalUpdateTransform();
        updateGpsMarker();
    }

    // replace updateTransform reference used elsewhere
    updateTransform = wrappedUpdateTransform;
});