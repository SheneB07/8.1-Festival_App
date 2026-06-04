document.addEventListener("DOMContentLoaded", () => {

    const mapWidth = 900;
    const mapHeight = 500;

    const map = L.map("festival-map", {
        crs: L.CRS.Simple,
        minZoom: -2,
        maxZoom: 3,
        maxBoundsViscosity: 1.0
    });

    const bounds = [
        [0, 0],
        [mapHeight, mapWidth]
    ];

    L.imageOverlay("assets/map/map.svg", bounds).addTo(map);

    map.setMaxBounds(bounds);
    map.fitBounds(bounds);

    const mapBounds = {
        lat1: 52.0,
        lon1: 4.3,
        lat2: 51.9,
        lon2: 4.4
    };

    function latLonToMap(lat, lon) {
        const x = ((lon - mapBounds.lon1) / (mapBounds.lon2 - mapBounds.lon1)) * mapWidth;
        const yRaw = ((lat - mapBounds.lat1) / (mapBounds.lat2 - mapBounds.lat1)) * mapHeight;

        return [mapHeight - yRaw, x];
    }

    // -------------------------
    // USER LOCATION
    // -------------------------

    const userMarker = L.marker([0, 0], {
        icon: L.icon({
            iconUrl: "assets/map/user.png",
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        })
    }).addTo(map);

    function startLiveLocation() {

        if (!navigator.geolocation) return;

        navigator.geolocation.watchPosition((pos) => {

            const coords = latLonToMap(
                pos.coords.latitude,
                pos.coords.longitude
            );

            userMarker.setLatLng(coords);

        }, console.error, {
            enableHighAccuracy: true,
            maximumAge: 1000,
            timeout: 10000
        });
    }

    startLiveLocation();

    // -------------------------
    // MARKERS
    // -------------------------

    markers.forEach(marker => {

        const icon = L.icon({
            iconUrl: marker.img,
            iconSize: [marker.width, marker.width],
            iconAnchor: [marker.width / 2, marker.width / 2]
        });

        const fixedY = mapHeight - marker.y_coords;

        const leafletMarker = L.marker(
            [fixedY, marker.x_coords],
            { icon }
        ).addTo(map);

        leafletMarker.on("click", async () => {

            let html = "";

            if (marker.types !== "stage") {

                switch (marker.types) {

                    case "toilet":
                        html = `<h3>Toilets</h3><p>Restrooms available here.</p>`;
                        break;

                    case "food":
                        html = `<h3>Food</h3><p>Food stands located here.</p>`;
                        break;

                    case "info":
                        html = `<h3>Info Point</h3><p>Get help and information here.</p>`;
                        break;

                    default:
                        html = `<h3>${marker.types}</h3>`;
                }

                mapModalContent.innerHTML = html;
                modal.style.display = "block";
                return;
            }

            try {

                const res = await fetch("getStageSchedule.php?id=" + marker.stage_id);
                const data = await res.json();

                html = "";

                if (marker.stage_image) {
                    html += `<img src="${marker.stage_image}" class="stage-image">`;
                }

                html += `
                    <h3>${marker.stage_name}</h3>
                    <h3>Stage Schedule</h3>
                `;

                if (!data.length) {
                    html += `<p>No performances found.</p>`;
                } else {
                    data.forEach(show => {
                        html += `
                            <div class="show-item">
                                <div>${show.day}</div>
                                <div>${show.start} - ${show.end}</div>
                                <div><a class="stage-artist-link" href="music.php?artist_id=${show.artist_id}&day=${encodeURIComponent(show.day)}">${show.artist}</a></div>
                            </div>
                        `;
                    });
                }

                mapModalContent.innerHTML = html;
                modal.style.display = "block";

            } catch (err) {
                console.error(err);
            }
        });
    });

});