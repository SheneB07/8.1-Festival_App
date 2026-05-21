<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">  
</head>
<body>
    <?php include("connection.php");
    try {
    // Prepare the query to select the name and description
    $stmt = $conn->prepare("SELECT titel, informatie, id FROM informatie");
    $stmt->execute();

    // Set the fetch mode to associative array
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $informatie = $stmt->fetchAll();  // This stores the result in the variable you use below

    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
    ?>
    <?php
    include("includes/header.php");
    ?>

    <?php 
    foreach($informatie as $info):
    ?>
    <div class="content">
        <div id="information">
            <h3><?php echo $info['titel']; ?></h3>
            <h3><?php echo $info['informatie']; ?></h3>
        </div>
    </div>
    <?php endforeach; ?>


    <?php 
    include("includes/footer.php");
    ?>
</body>
</html>