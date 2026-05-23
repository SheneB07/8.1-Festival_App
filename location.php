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

        <div id="map-inner">
            <img src="assets/map/map.svg" id="map">
        </div>

        <div id="marker-layer">
            <!-- number markers -->
            <img src="assets/map/marker_1.svg"  class="marker" data-x="180" data-y="320">
            <img src="assets/map/marker_2.svg"  class="marker" data-x="475" data-y="230">
            <img src="assets/map/marker_3.svg"  class="marker" data-x="610" data-y="200">
            <img src="assets/map/marker_4.svg"  class="marker" data-x="790" data-y="90">

            <!-- bar markers -->
            <img src="assets/map/marker_bar.svg"  class="marker" data-x="710" data-y="140" style="width: 20px">
            <img src="assets/map/marker_bar.svg"  class="marker" data-x="630" data-y="150" style="width: 20px">
            <img src="assets/map/marker_bar.svg"  class="marker" data-x="450" data-y="205" style="width: 20px">
            <img src="assets/map/marker_bar.svg"  class="marker" data-x="100" data-y="375" style="width: 20px">

            <!-- entrance/exit marker -->
            <img src="assets/map/marker_entrance_exit.svg"  class="marker" data-x="605" data-y="430" style="width: 70px">

            <!-- first aid marker -->
            <img src="assets/map/marker_first_aid.svg"  class="marker" data-x="160" data-y="155" style="width: 20px">

            <!-- food markers -->
            <img src="assets/map/marker_food.svg"  class="marker" data-x="105" data-y="315" style="width: 20px">
            <img src="assets/map/marker_food.svg"  class="marker" data-x="310" data-y="220" style="width: 20px">

            <!-- ice cream markers -->
            <img src="assets/map/marker_ice_cream.svg"  class="marker" data-x="230" data-y="340" style="width: 20px">
            <img src="assets/map/marker_ice_cream.svg"  class="marker" data-x="350" data-y="200" style="width: 20px">
            <img src="assets/map/marker_ice_cream.svg"  class="marker" data-x="545" data-y="170" style="width: 20px">
            <img src="assets/map/marker_ice_cream.svg"  class="marker" data-x="730" data-y="85" style="width: 20px">

            <!-- locker markers -->
            <img src="assets/map/marker_locker.svg"  class="marker" data-x="210" data-y="415" style="width: 25px">
            <img src="assets/map/marker_locker.svg"  class="marker" data-x="265" data-y="415" style="width: 25px">

            <!-- merchandise markers -->
            <img src="assets/map/marker_merchandise.svg"  class="marker" data-x="150" data-y="400" style="width: 20px">
            <img src="assets/map/marker_merchandise.svg"  class="marker" data-x="280" data-y="200" style="width: 20px">
            <img src="assets/map/marker_merchandise.svg"  class="marker" data-x="570" data-y="210" style="width: 20px">

            <!-- toilet markers -->
            <img src="assets/map/marker_toilet.svg"  class="marker" data-x="70" data-y="400" style="width: 20px">
            <img src="assets/map/marker_toilet.svg"  class="marker" data-x="430" data-y="140" style="width: 20px">
            <img src="assets/map/marker_toilet.svg"  class="marker" data-x="815" data-y="130" style="width: 20px">
            </div>
        </div>
    </div>

    <?php 
    include("includes/footer.php");
    ?>

    <script src="map.js"></script>
</body>
</html>