<?php
include("connection.php");

/*
|--------------------------------------------------------------------------
| Load markers
|--------------------------------------------------------------------------
*/
try {

    $stmt = $conn->prepare("
    SELECT
        markers.marker_id,
        markers.stage_id,
        markers.x_coords,
        markers.y_coords,
        markers.img,
        markers.width,
        markers.types,

        stages.name AS stage_name,
        stages.stage_image AS stage_image

    FROM markers

    LEFT JOIN stages
        ON markers.stage_id = stages.id
");

    $stmt->execute();

    $markers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Marker error: " . $e->getMessage());

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("includes/header.php"); ?>

<div class="content">
    <button id="locate-btn" class="locate-btn">Locate me</button>
    <div id="map-box">

    

        

        <div id="map-inner" data-lat1="" data-lon1="" data-lat2="" data-lon2="">
            <img src="assets/map/map.svg" id="map">
        </div>

        <div id="marker-layer">

            <?php foreach($markers as $marker): ?>

                <img
                    src="<?= htmlspecialchars($marker['img']) ?>"
                    class="marker marker-box"

                    data-stage="<?= $marker['stage_id'] ?>"
                    data-stage-name="<?= htmlspecialchars($marker['stage_name']) ?>"
                    data-stage-image="<?= htmlspecialchars($marker['stage_image']) ?>"

                    data-type="<?= htmlspecialchars($marker['types']) ?>"
                    data-x="<?= $marker['x_coords'] ?>"
                    data-y="<?= $marker['y_coords'] ?>"
                    style="width: <?= $marker['width'] ?>px;"
                >

            <?php endforeach; ?>

        </div>

    </div>

</div>

<div id="map-modal" class="map-modal">
    <div id="map-modal-content"></div>
</div>

<?php include("includes/footer.php"); ?>

<script src="map.js"></script>

<script>

const modal = document.getElementById("map-modal");
const mapModalContent = document.getElementById("map-modal-content");


function translateDay(day) {

    if(currentLanguage === "en") {

        switch(day.toLowerCase()) {
            case "zaterdag":
                return "Saturday";

            case "zondag":
                return "Sunday";

            default:
                return day;
        }

    }

    return day;
}

document.querySelectorAll(".marker-box").forEach(box => {

    box.addEventListener("click", async () => {

    const type = box.dataset.type;
    const stageId = box.dataset.stage;
    const stageName = box.dataset.stageName;
    const stageImage = box.dataset.stageImage;

    let html = "";

    // -------------------------
    // NON-STAGE MARKERS
    // -------------------------
    if (type !== "stage") {

        switch (type) {

            case "toilet":
                html = `
                    <h3>${currentLanguage === "en" ? "Toilets" : "Toiletten"}</h3>
                    <p>${currentLanguage === "en"
                        ? "Restrooms available here."
                        : "Toiletten beschikbaar hier."}
                    </p>
                `;
                break;

            case "food":
                html = `
                    <h3>${currentLanguage === "en" ? "Food" : "Eten"}</h3>
                    <p>${currentLanguage === "en"
                        ? "Food stands located here."
                        : "Eetkraampjes hier."}
                    </p>
                `;
                break;

            case "info":
                html = `
                    <h3>${currentLanguage === "en" ? "Info Point" : "Informatiepunt"}</h3>
                    <p>${currentLanguage === "en"
                        ? "Get help and information here."
                        : "Hier kun je informatie krijgen."}
                    </p>
                `;
                break;

            default:
                html = `<h3>${type}</h3>`;
        }

        mapModalContent.innerHTML = html;
        modal.style.display = "block";
        return; // 🔥 THIS WAS MISSING (prevents overwrite bug)
    }

    // -------------------------
    // STAGE MARKERS ONLY
    // -------------------------
    try {

        const response = await fetch("getStageSchedule.php?id=" + stageId);
        const data = await response.json();

        html = "";

        if (stageImage) {
            html += `<img src="${stageImage}" class="stage-image">`;
        }

        html += `
            <h2 class="stage-title">${stageName}</h2>
            <h3>${currentLanguage === "en" ? "Stage Schedule" : "Podiumschema"}</h3>
        `;

        if (data.length === 0) {
            html += `<p>${currentLanguage === "en"
                ? "No performances found."
                : "Geen optredens gevonden."
            }</p>`;
        } else {
            data.forEach(show => {
                html += `
                    <div class="show-item">
                        <div class="show-day">
                            ${translateDay(show.day)}
                        </div>

                        <div class="show-time">
                            ${show.start} - ${show.end}
                        </div>

                        <div class="show-artist">
                            ${show.artist}
                        </div>
                    </div>
                `;
            });
        }

        mapModalContent.innerHTML = html;
        modal.style.display = "block";

    } catch (error) {
        console.error(error);

        mapModalContent.innerHTML = `<p>${
            currentLanguage === "en"
                ? "Error loading schedule."
                : "Fout bij laden van het schema."
        }</p>`;

        modal.style.display = "block";
    }
});

});

window.addEventListener("click", e => {

    if(e.target === modal){

        modal.style.display = "none";

    }

});

</script>

</body>
</html>