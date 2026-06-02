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
    $stmt = $conn->prepare("SELECT titel, informatie, id, titel_en, informatie_en FROM informatie");
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
    <div class="info-content">
        <div id="information">
    <?php 
    foreach($informatie as $info):
    ?>
            <button
    class="accordion"
    data-nl-title="<?= htmlspecialchars($info['titel']) ?>"
    data-en-title="<?= htmlspecialchars($info['titel_en']) ?>"
>
    <?= htmlspecialchars($info['titel']) ?>
</button>

<div
    class="panel"
    data-nl-content="<?= htmlspecialchars($info['informatie']) ?>"
    data-en-content="<?= htmlspecialchars($info['informatie_en']) ?>"
>
    <?= $info['informatie'] ?>
</div>
    <?php endforeach; ?>
    </div>
</div>

    <?php 
    include("includes/footer.php");
    ?>

    <script>
        var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    /* Toggle between adding and removing the "active" class,
    to highlight the button that controls the panel */
    this.classList.toggle("active");

    /* Toggle between hiding and showing the active panel */
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}



const accordions = document.querySelectorAll(".accordion");
const panels = document.querySelectorAll(".panel");

if (currentLanguage === "en") {

    accordions.forEach(acc => {
        acc.textContent = acc.dataset.enTitle;
    });

    panels.forEach(panel => {
        panel.innerHTML = panel.dataset.enContent;
    });

} else {

    accordions.forEach(acc => {
        acc.textContent = acc.dataset.nlTitle;
    });

    panels.forEach(panel => {
        panel.innerHTML = panel.dataset.nlContent;
    });
}
    </script>
</body>
</html>