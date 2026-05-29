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
        <h5>CMS</h5>
        <hr>
        <div class="edit-container">
        <div class="buttons-container" id="buttons-container">
        <h6>Bijwerken:</h6>
        <div id="option-button">Nieuws & meldingen</div>
        <div id="option-button" onclick="swapEdits()">Festival informatie</div>
        <div id="option-button" onclick="swapEdits()">Optredingen programma</div>
        <div id="option-button">Map</div>
        </div>

        <!-- Edit Information page -->
<div class="edit-information" id="edit-information">

<h3>Festival informatie</h3>

<?php foreach($informatie as $info): ?>

<form method="POST" class="cms-form">

    <input 
        type="hidden"
        name="id"
        value="<?= $info['id']; ?>"
    >

    <label>Titel:</label>

    <input 
        type="text"
        name="titel"
        value="<?= htmlspecialchars($info['titel']); ?>"
    >

    <label>Informatie:</label>

    <textarea name="informatie"><?= htmlspecialchars($info['informatie']); ?></textarea>

    <button type="submit">Opslaan</button>

</form>

<hr>

<?php endforeach; ?>

</div>

        <!-- Edit programma page -->
         <div class="edit-programma" id="edit-programma">

         </div>
       </div>
    </div>

    <script>
        function swapEdits() {
    const b1 = document.getElementById("buttons-container");
    const b2 = document.getElementById("edit-information");
    const b3 = document.getElementById("edit-programma");
    
     if (b1.style.display === "none") {
        b1.style.display = "block";
        b2.style.display = "none";
        b3.style.display = "none";
        
    } else {
        b1.style.display = "none";
        b2.style.display = "flex";
        b3.style.display = "none";
}
}
    </script>

</body>
</html>