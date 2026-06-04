<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Festival Music & Schedule</title>
    <meta name="description" content="Festival artist schedule by day and stage">    <link rel="stylesheet" href="style.css">  
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#0a74da">
</head>
<?php 
$selectedDay = $_GET['day'] ?? 'zaterdag';
?>
<?php
// Convert time → pixels
function timeToPixels($time) {
  $startOfDay = strtotime("2024-01-01 10:00:00");
  $current = strtotime("2024-01-01 " . date("H:i:s", strtotime($time)));
  

  $minutes = ($current - $startOfDay) / 60;
  return $minutes * 2; // 1 min = 2px
}
?>

<?php 
include("connection.php");

try {
$stmt = $conn->prepare(" SELECT 
    performances.start AS start_time,
    performances.end AS end_time,
    performances.stage,
    artists.naam,
    artists.naam_en,
    artists.omschrijving,
    artists.omschrijving_en,
    artists.afbeelding,
    artists.video,
    artists.tekst,
    artists.tekst_en,
    artists.id,
    stages.name AS stage_name
  FROM performances
  JOIN artists ON performances.artist_id = artists.id
  JOIN stages ON performances.stage = stages.id
  WHERE performances.day = :day
");
$stmt->execute(['day' => $selectedDay]);
$acts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$stmt = $conn->prepare("SELECT * FROM stages");
$stmt->execute();
$stagesData = $stmt->fetchAll(PDO::FETCH_ASSOC);



$stages = [];
$rowIndex = 2; // row 1 = time header

foreach ($stagesData as $stage) {
  $stages[$stage['id']] = [
    "name" => $stage['name'],
    "row" => $rowIndex
  ];
  $rowIndex++;
}


$conn = null;
?>

<body>

<?php include("includes/header.php"); ?>



<div class="music-content">
<div class="day-switch">
  <a href="?day=zaterdag"
     class="day-link <?= $selectedDay == 'zaterdag' ? 'active' : '' ?>"
     data-nl="Zaterdag"
     data-en="Saturday">
    Zaterdag
  </a>

  <a href="?day=zondag"
     class="day-link <?= $selectedDay == 'zondag' ? 'active' : '' ?>"
     data-nl="Zondag"
     data-en="Sunday">
    Zondag
  </a>
</div>

<div style="overflow-x: scroll;">
  <div class="schedule">
  <div class="grid-cell"></div>

  <?php for ($h = 10; $h <= 23; $h++): ?>
    <div class="time-header"><?= $h ?>:00</div>
  <?php endfor; ?>

  <?php foreach ($stages as $stage): ?>
  <div class="stage-label"
     style="grid-column: 1; grid-row: <?= $stage['row'] ?>;">
    <?= $stage['name'] ?>
  </div>

  <?php for ($h = 10; $h <= 23; $h++): ?>
    
  <?php endfor; ?>
<?php endforeach; ?>

    <!-- Events -->
    <?php foreach ($acts as $act):

  $start = strtotime($act['start_time']);
  $end   = strtotime($act['end_time']);

  $startHour = (int)date("H", $start);
  $endHour   = (int)date("H", $end);

  /* GRID columns */
  $colStart = ($startHour - 10) + 2;
  $colEnd   = ($endHour - 10) + 2;

  /* GRID row */
  $row = $stages[$act['stage']]['row'];

?>

<div class="event event-box"

  data-id="<?= $act['id'] ?>"
  data-name-nl="<?= htmlspecialchars($act['naam']) ?>"
  data-name-en="<?= htmlspecialchars($act['naam_en']) ?>"

  data-description-nl="<?= htmlspecialchars($act['omschrijving']) ?>"
  data-description-en="<?= htmlspecialchars($act['omschrijving_en']) ?>"

  data-start="<?= date('H:i', strtotime($act['start_time'])) ?>"
  data-end="<?= date('H:i', strtotime($act['end_time'])) ?>"


  data-info-nl="<?= htmlspecialchars($act['tekst']) ?>"
  data-info-en="<?= htmlspecialchars($act['tekst_en']) ?>"

  data-image="<?= htmlspecialchars($act['afbeelding']) ?>"
  data-video="<?= htmlspecialchars($act['video']) ?>"



  style="
    grid-column: <?= $colStart ?> / <?= $colEnd ?>;
    grid-row: <?= $row ?>;
  ">
  
  <?= htmlspecialchars($act['naam']) ?>
</div>



<?php endforeach; ?>


  </div>
</div>
</div>

<div id="info-modal" class="modal">
  <div id="modal-content">
    
  </div>
</div>


<?php include("includes/footer.php"); ?>

<script>
var modal = document.getElementById("info-modal");
var modalContent = document.getElementById("modal-content");
var boxes = document.querySelectorAll(".event-box");

boxes.forEach(function(box) {

  box.onclick = function() {

  const name = currentLanguage === "nl"
      ? box.dataset.nameNl
      : box.dataset.nameEn;

  const description = currentLanguage === "nl"
      ? box.dataset.descriptionNl
      : box.dataset.descriptionEn;

  const info = currentLanguage === "nl"
      ? box.dataset.infoNl
      : box.dataset.infoEn;

  const imageHtml = box.dataset.image
      ? `<img src="${box.dataset.image}" class="modal-image">`
      : '';

  modalContent.innerHTML = `
    ${imageHtml}

    <div class="modal-body">
      <h3 class="artist-title">${name}</h3>

      <p class="artist-description">
        ${description}
      </p>

      <div class="artist-time">
      ${box.dataset.start} - ${box.dataset.end}
      </div>

      <div class="artist-info">
        ${info}
      </div>
    </div>
  `;

  modal.style.display = "block";
};

});

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

function openArtistModalById(artistId) {
  const targetBox = Array.from(boxes).find(box => box.dataset.id === artistId);
  if (targetBox) {
    targetBox.click();
  }
}

const urlParams = new URLSearchParams(window.location.search);
const artistIdParam = urlParams.get('artist_id');
if (artistIdParam) {
  openArtistModalById(artistIdParam);
}

document.querySelectorAll(".day-link").forEach(link => {
    link.textContent =
        currentLanguage === "nl"
            ? link.dataset.nl
            : link.dataset.en;
});

</script>

</body>
</html>