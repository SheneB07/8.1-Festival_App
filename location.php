<?php
include("connection.php");

$stmt = $conn->prepare("
    SELECT
        m.marker_id,
        m.stage_id,
        m.x_coords,
        m.y_coords,
        m.img,
        m.width,
        m.types,
        s.name AS stage_name,
        s.stage_image AS stage_image
    FROM markers m
    LEFT JOIN stages s ON m.stage_id = s.id
");

$stmt->execute();
$markers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festival Map</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
</head>

<body>

<?php include("includes/header.php"); ?>

<div class="content">
    <div id="map-box">
        <div id="festival-map"></div>
    </div>
</div>

<div id="map-modal" class="map-modal">
    <div id="map-modal-content"></div>
</div>

<?php include("includes/footer.php"); ?>

<script>
const markers = <?= json_encode($markers) ?>;

const modal = document.getElementById("map-modal");
const mapModalContent = document.getElementById("map-modal-content");

window.addEventListener("click", e => {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});
</script>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="map.js"></script>

</body>
</html>