<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php

include("connection.php");

/* UPDATE */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];
    $titel = $_POST['titel'];
    $informatie = $_POST['informatie'];

    $update = $conn->prepare("
        UPDATE informatie
        SET titel = ?, informatie = ?
        WHERE id = ?
    ");

    $update->execute([
        $titel,
        $informatie,
        $id
    ]);
}

/* GET DATA */

$stmt = $conn->prepare("
    SELECT id, titel, informatie 
    FROM informatie
");

$stmt->execute();

$informatie = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
    <?php include("includes/header.php"); ?>
    <div class="cms-content">

    <!-- MENU -->
     <div class="cms-content">
        <h5>CMS</h5>
        <hr>
        <div class="edit-container">
        <div class="buttons-container" id="buttons-container">
        <h6>Bijwerken:</h6>
        <div id="option-button" onclick="showSection('edit-news')">Nieuws & meldingen</div>
        <div id="option-button" onclick="showSection('edit-information')">Festival informatie</div>
        <div id="option-button" onclick="showSection('edit-programma')">Optredingen programma</div>
        <div id="option-button" onclick="showSection('edit-map')">Map</div>
        </div>


    <!-- INFORMATION -->
    <div id="edit-information" class="cms-section">

        <button class="back-btn" onclick="showMenu()">
            ← Terug
        </button>

        <h3>Festival Informatie</h3>

        <?php foreach($informatie as $info): ?>

        <form method="POST" class="cms-form">

            <input
                type="hidden"
                name="id"
                value="<?= $info['id']; ?>"
            >

            <label>Titel</label>

            <input
                type="text"
                name="titel"
                value="<?= htmlspecialchars($info['titel']); ?>"
            >

            <label>Informatie</label>

            <textarea name="informatie"><?= htmlspecialchars($info['informatie']); ?></textarea>

            <button type="submit">Opslaan</button>

        </form>

        <hr>

        <?php endforeach; ?>

    </div>


    <!-- NEWS -->
    <div id="edit-news" class="cms-section">

        <button class="back-btn" onclick="showMenu()">
            ← Terug
        </button>

        <h3>Nieuws & Meldingen</h3>

        

    </div>


    <!-- PROGRAMMA -->
    <div id="edit-programma" class="cms-section">

        <button class="back-btn" onclick="showMenu()">
            ← Terug
        </button>

        <h3>Programma</h3>

        <!-- Add your programme CMS here -->

    </div>


    <!-- MAP -->
    <div id="edit-map" class="cms-section">

        <button class="back-btn" onclick="showMenu()">
            ← Terug
        </button>

        <h3>Festival Map</h3>

        <img src="assets/map/map.svg" id="cms-map">

        <div id="marker-layer">
            <!-- markers -->
        </div>

        <div class="marker-form">

            <form action="save-marker.php" method="POST">

                <input type="hidden" name="marker_id">

                <label>Title</label>
                <input type="text" name="title">

                <label>Description</label>
                <textarea name="description"></textarea>

                <label>X Position</label>
                <input type="number" name="x_coords">

                <label>Y Position</label>
                <input type="number" name="y_coords">

                <label>Image</label>
                <input type="text" name="img">

                <label>Width</label>
                <input type="number" name="width">

                <button type="submit">
                    Save Marker
                </button>

            </form>

        </div>

    </div>

</div>
</body>
</html>


<script>
    const sections = document.querySelectorAll(".cms-section");
const menu = document.getElementById("buttons-container");

function showSection(sectionId){

    menu.style.display = "none";

    sections.forEach(section => {
        section.style.display = "none";
    });

    document.getElementById(sectionId).style.display = "block";
}

function showMenu(){

    sections.forEach(section => {
        section.style.display = "none";
    });

    menu.style.display = "block";
}
</script>