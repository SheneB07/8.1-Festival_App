<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
    <?php include("connection.php");
    try {
    // Prepare the query to select the name and description
    $stmt = $conn->prepare("SELECT information_id, information FROM nieuws_meldingen");
    $stmt->execute();

    // Set the fetch mode to associative array
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $nieuws_meldingen = $stmt->fetchAll();  // This stores the result in the variable you use below

    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
    ?>
<body>
    <?php 
    include("includes/header.php");
    ?>

    <?php foreach($nieuws_meldingen as $nieuw_melding): ?>
    <div class="content">
        <div id="information-box">
            <h3><?= htmlspecialchars($nieuw_melding['information']) ?></h3>
        </div>
</div>
<?php endforeach; ?>
    <?php 
    include("includes/footer.php");
    ?>
</body>
</html>