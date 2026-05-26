<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">  
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
    artists.omschrijving,
    artists.afbeelding,
    artists.video,
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
  <a href="?day=zaterdag" class="<?= $selectedDay == 'zaterdag' ? 'active' : '' ?>">
    Zaterdag
  </a>
  <a href="?day=zondag" class="<?= $selectedDay == 'zondag' ? 'active' : '' ?>">
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
    <div id="acts"></div>
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

<div class="event"
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
<?php include("includes/footer.php"); ?>

</body>
</html>