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
            <!-- number markers -->
            <img src="assets/map/marker_1.svg"  class="marker" style="left: 180px; top: 320px">
            <img src="assets/map/marker_2.svg"  class="marker" style="left: 475px; top: 230px">
            <img src="assets/map/marker_3.svg"  class="marker" style="left: 610px; top: 200px">
            <img src="assets/map/marker_4.svg"  class="marker" style="left: 790px; top: 90px">

            <!-- bar markers -->
            <img src="assets/map/marker_bar.svg"  class="place-marker" style="left: 710px; top: 140px">
            <img src="assets/map/marker_bar.svg"  class="place-marker" style="left: 630px; top: 150px">
            <img src="assets/map/marker_bar.svg"  class="place-marker" style="left: 455px; top: 205px">
            <img src="assets/map/marker_bar.svg"  class="place-marker" style="left: 100px; top: 375px">

            <!-- entrance/exit marker -->
            <img src="assets/map/marker_entrance_exit.svg"  class="place-marker" style="left: 605px; top: 430px; width: 60px">

            <!-- first aid marker -->
            <img src="assets/map/marker_first_aid.svg"  class="place-marker" style="left: 160px; top: 155px">

            <!-- food markers -->
            <img src="assets/map/marker_food.svg"  class="place-marker" style="left: 105px; top: 315px">
            <img src="assets/map/marker_food.svg"  class="place-marker" style="left: 310px; top: 220px">

            <!-- ice cream markers -->
            <img src="assets/map/marker_ice_cream.svg"  class="place-marker" style="left: 230px; top: 340px">
            <img src="assets/map/marker_ice_cream.svg"  class="place-marker" style="left: 350px; top: 200px">
            <img src="assets/map/marker_ice_cream.svg"  class="place-marker" style="left: 545px; top: 170px">
            <img src="assets/map/marker_ice_cream.svg"  class="place-marker" style="left: 730px; top: 85px">

            <!-- locker markers -->
            <img src="assets/map/marker_locker.svg"  class="place-marker" style="left: 210px; top: 415px; width: 60px">
            <img src="assets/map/marker_locker.svg"  class="place-marker" style="left: 265px; top: 415px; width: 60px">

            <!-- merchandise markers -->
            <img src="assets/map/marker_merchandise.svg"  class="place-marker" style="left: 150px; top: 400px;">
            <img src="assets/map/marker_merchandise.svg"  class="place-marker" style="left: 280px; top: 200px;">
            <img src="assets/map/marker_merchandise.svg"  class="place-marker" style="left: 570px; top: 210px;">

            <!-- toilet markers -->
            <img src="assets/map/marker_toilet.svg"  class="place-marker" style="left: 70px; top: 400px;">
            <img src="assets/map/marker_toilet.svg"  class="place-marker" style="left: 430px; top: 140px;">
            <img src="assets/map/marker_toilet.svg"  class="place-marker" style="left: 815px; top: 130px;">
        </div>
    </div>

    <?php 
    include("includes/footer.php");
    ?>
</body>
</html>