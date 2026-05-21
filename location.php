<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">  
</head>
<body>
    <?php 
    include("includes/header.php");
    ?>

    <div class="content">
        <div id="map-box">
            <img src="assets/map/map.svg" id="map">

            <img src="assets/map/marker_1.svg"  class="marker" style="left: 180px; top: 320px">

            <img src="assets/map/marker_2.svg"  class="marker" style="left: 475px; top: 230px">

            <img src="assets/map/marker_3.svg"  class="marker" style="left: 610px; top: 200px">

            <img src="assets/map/marker_4.svg"  class="marker" style="left: 790px; top: 90px">

            <img src="assets/map/marker_bar.svg"  class="place-marker" style="left: 710px; top: 140px">
        </div>
    </div>

    <?php 
    include("includes/footer.php");
    ?>
</body>
</html>