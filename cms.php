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
    ?>

    
    
    <?php 
    include("includes/header.php");
    ?>

    <div class="cms-content">
        <h5>CMS</h5>
        <hr>
        <div class="edit-container">
       <div class="buttons-container">
        <h6>Bijwerken:</h6>
        <div id="option-button">Festival informatie</div>
        <div id="option-button">Optredingen programma</div>
        <div id="option-button">Map</div>
       </div>
       <div class="option-container">
            <div class="edit-information">
                Information
            </div>
       </div>
       </div>
    </div>

</body>
</html>