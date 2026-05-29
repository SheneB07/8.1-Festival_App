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
    $stmt = $conn->prepare("SELECT marker_id, x_coords, y_coords, img, width, types FROM markers");
    $stmt->execute();

    // Set the fetch mode to associative array
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $markers = $stmt->fetchAll();  // This stores the result in the variable you use below

    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
    ?>


    <?php 
    include("includes/header.php");
    ?>

    <div class="content">
        <div id="map-box">

        <div id="map-inner">
            <img src="assets/map/map.svg" id="map">
        </div>
        
        <div id="marker-layer">

        <!-- markers -->
<?php foreach($markers as $marker) { ?>

    <img 
        src="<?= $marker['img']; ?>"
        class="marker marker-box"
        data-x="<?= $marker['x_coords']; ?>"
        data-y="<?= $marker['y_coords']; ?>"
        data-types="<?= htmlspecialchars($marker['types']) ?>"
        style="width: <?= $marker['width']; ?>px;"
        
    >
    
<?php } ?>
        

            
            </div>
        </div>
    </div>

    <div id="info-modal" class="modal">
  <div id="modal-content">
    
  </div>
</div>

    <?php 
    include("includes/footer.php");
    ?>

    <script src="map.js"></script>

<script>
var modal = document.getElementById("info-modal");
var modalContent = document.getElementById("modal-content");
var boxes = document.querySelectorAll(".marker-box");

boxes.forEach(function(box) {

  box.onclick = function() {

    var types = box.dataset.types;

    

    modalContent.innerHTML = `
      

      <p>${types}</p>

      
    `;

    modal.style.display = "block";
  }

});

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
</body>
</html>