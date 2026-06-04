<?php

include("connection.php");

header('Content-Type: application/json');

if (!isset($_GET['id'])) {

    echo json_encode([]);
    exit;

}

$stageId = (int)$_GET['id'];

$stmt = $conn->prepare("
    SELECT
        performances.day,
        performances.start,
        performances.end,
        artists.naam AS artist,
        artists.id AS artist_id
    FROM performances
    JOIN artists
        ON artists.id = performances.artist_id
    WHERE performances.stage = ?
    ORDER BY performances.day, performances.start
");

$stmt->execute([$stageId]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);