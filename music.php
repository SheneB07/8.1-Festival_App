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
$top = 0;

foreach ($stagesData as $stage) {
  $stages[$stage['id']] = [
    "name" => $stage['name'],
    "top" => $top
  ];
  $top += 80;
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
  <div class="timeline">
<?php
for ($hour = 10; $hour <= 23; $hour++):
  $left = ($hour - 10) * 60 * 2;
?>
  <div class="time-label" style="left: <?= $left ?>px;">
    <?= sprintf('%02d:00', $hour) ?>
  </div>
<?php endfor; ?>
</div>
  <div class="schedule">

    <!-- Timeline -->
     

    <!-- Stage rows -->
    <?php foreach ($stages as $stage): ?>
      <div class="stage" id="stage-name" style="top: <?= $stage['top'] ?>px;">
        <?= $stage['name']?>
      </div>
    <?php endforeach; ?>

    <!-- Events -->
    <?php foreach ($acts as $act): 
  $left = timeToPixels($act['start_time']);
  $right = timeToPixels($act['end_time']);
  $width = max(50, $right - $left);

  $top = isset($stages[$act['stage']]) 
    ? $stages[$act['stage']]['top'] 
    : 0;
?>

  <div class="event"
    style="
      position:absolute;
      left: <?= $left + 100 ?>px;
      width: <?= $width ?>px;
      top: <?= $top + 10 ?>px;
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