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
            marker_id,
            stage_id,
            x_coords,
            y_coords,
            img,
            width,
            types
        FROM markers
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

    <div id="map-box">

        <div id="map-inner">
            <img src="assets/map/map.svg" id="map">
        </div>

        <div id="marker-layer">

            <?php foreach($markers as $marker): ?>

                <img
                    src="<?= htmlspecialchars($marker['img']) ?>"
                    class="marker marker-box"
                    data-stage="<?= $marker['stage_id'] ?>"
                    data-x="<?= $marker['x_coords'] ?>"
                    data-y="<?= $marker['y_coords'] ?>"
                    style="width: <?= $marker['width'] ?>px;"
                >

            <?php endforeach; ?>

        </div>

    </div>

</div>

<div id="info-modal" class="modal">
    <div id="modal-content"></div>
</div>

<?php include("includes/footer.php"); ?>

<script src="map.js"></script>

<script>

const modal = document.getElementById("info-modal");
const modalContent = document.getElementById("modal-content");

document.querySelectorAll(".marker-box").forEach(box => {

    box.addEventListener("click", async () => {

        const stageId = box.dataset.stage;

        try {

            const response = await fetch(
                "getStageSchedule.php?id=" + stageId
            );

            const data = await response.json();

            let html = "<h3>Stage Schedule</h3>";

            if(data.length === 0){

                html += "<p>No performances found.</p>";

            } else {

                data.forEach(show => {

                    html += `
                        <div class="show-item">
                            <strong>${show.day}</strong><br>
                            ${show.start} - ${show.end}<br>
                            ${show.artist}
                        </div>
                        <hr>
                    `;

                });

            }

            modalContent.innerHTML = html;
            modal.style.display = "block";

        } catch(error) {

            console.error(error);

            modalContent.innerHTML =
                "<p>Error loading schedule.</p>";

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