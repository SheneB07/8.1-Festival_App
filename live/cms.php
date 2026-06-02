<?php
include("connection.php");

$feedback = '';
$activeSection = $_GET['section'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action'])) {
    try {
        switch ($_POST['action']) {
            case 'add_information':
                $stmt = $conn->prepare("INSERT INTO informatie (titel, informatie) VALUES (?, ?)");
                $stmt->execute([
                    $_POST['titel'] ?? '',
                    $_POST['informatie'] ?? ''
                ]);
                header('Location: cms.php?section=edit-information');
                exit;

            case 'save_information':
                $stmt = $conn->prepare("UPDATE informatie SET titel = ?, informatie = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['titel'] ?? '',
                    $_POST['informatie'] ?? '',
                    $_POST['id']
                ]);
                header('Location: cms.php?section=edit-information');
                exit;

            case 'delete_information':
                $stmt = $conn->prepare("DELETE FROM informatie WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                header('Location: cms.php?section=edit-information');
                exit;

            case 'add_news':
                $stmt = $conn->prepare("INSERT INTO nieuws_meldingen (information) VALUES (?)");
                $stmt->execute([$_POST['information'] ?? '']);
                header('Location: cms.php?section=edit-news');
                exit;

            case 'save_news':
                $stmt = $conn->prepare("UPDATE nieuws_meldingen SET information = ? WHERE information_id = ?");
                $stmt->execute([
                    $_POST['information'] ?? '',
                    $_POST['information_id']
                ]);
                header('Location: cms.php?section=edit-news');
                exit;

            case 'delete_news':
                $stmt = $conn->prepare("DELETE FROM nieuws_meldingen WHERE information_id = ?");
                $stmt->execute([$_POST['information_id']]);
                header('Location: cms.php?section=edit-news');
                exit;

            case 'add_performance':
                $stmt = $conn->prepare("INSERT INTO performances (day, start, end, stage, artist_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['day'] ?? '',
                    $_POST['start'] ?? '',
                    $_POST['end'] ?? '',
                    $_POST['stage'] ?? '',
                    $_POST['artist_id'] ?? ''
                ]);
                header('Location: cms.php?section=edit-programma');
                exit;

            case 'save_performance':
                $stmt = $conn->prepare("UPDATE performances SET day = ?, start = ?, end = ?, stage = ?, artist_id = ? WHERE id = ? OR performance_id = ?");
                $stmt->execute([
                    $_POST['day'] ?? '',
                    $_POST['start'] ?? '',
                    $_POST['end'] ?? '',
                    $_POST['stage'] ?? '',
                    $_POST['artist_id'] ?? '',
                    $_POST['performance_id'],
                    $_POST['performance_id']
                ]);
                header('Location: cms.php?section=edit-programma');
                exit;

            case 'delete_performance':
                $stmt = $conn->prepare("DELETE FROM performances WHERE id = ? OR performance_id = ?");
                $stmt->execute([$_POST['performance_id'], $_POST['performance_id']]);
                header('Location: cms.php?section=edit-programma');
                exit;

            case 'add_marker':
                $stmt = $conn->prepare("INSERT INTO markers (stage_id, x_coords, y_coords, img, width, types) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['stage_id'] ?? '',
                    $_POST['x_coords'] ?? 0,
                    $_POST['y_coords'] ?? 0,
                    $_POST['img'] ?? '',
                    $_POST['width'] ?? 30,
                    $_POST['types'] ?? ''
                ]);
                header('Location: cms.php?section=edit-map');
                exit;

            case 'save_marker':
                $stmt = $conn->prepare("UPDATE markers SET stage_id = ?, x_coords = ?, y_coords = ?, img = ?, width = ?, types = ? WHERE marker_id = ?");
                $stmt->execute([
                    $_POST['stage_id'] ?? '',
                    $_POST['x_coords'] ?? 0,
                    $_POST['y_coords'] ?? 0,
                    $_POST['img'] ?? '',
                    $_POST['width'] ?? 30,
                    $_POST['types'] ?? '',
                    $_POST['marker_id']
                ]);
                header('Location: cms.php?section=edit-map');
                exit;

            case 'delete_marker':
                $stmt = $conn->prepare("DELETE FROM markers WHERE marker_id = ?");
                $stmt->execute([$_POST['marker_id']]);
                header('Location: cms.php?section=edit-map');
                exit;

            default:
                $feedback = 'Onbekende actie.';
                break;
        }
    } catch (PDOException $e) {
        $feedback = 'Database fout: ' . $e->getMessage();
    }
}

$informationStmt = $conn->prepare("SELECT id, titel, informatie FROM informatie ORDER BY id");
$informationStmt->execute();
$informationItems = $informationStmt->fetchAll(PDO::FETCH_ASSOC);

$newsStmt = $conn->prepare("SELECT information_id, information FROM nieuws_meldingen ORDER BY information_id");
$newsStmt->execute();
$newsItems = $newsStmt->fetchAll(PDO::FETCH_ASSOC);

$artistStmt = $conn->prepare("SELECT id, naam FROM artists ORDER BY naam");
$artistStmt->execute();
$artists = $artistStmt->fetchAll(PDO::FETCH_ASSOC);

$stageStmt = $conn->prepare("SELECT id, name FROM stages ORDER BY name");
$stageStmt->execute();
$stages = $stageStmt->fetchAll(PDO::FETCH_ASSOC);

$performanceStmt = $conn->prepare(
    "SELECT p.id AS performance_id, p.day, p.start, p.end, p.stage, p.artist_id,
            a.naam AS artist_name, s.name AS stage_name
     FROM performances p
     LEFT JOIN artists a ON a.id = p.artist_id
     LEFT JOIN stages s ON s.id = p.stage
     ORDER BY p.day, p.start"
);
$performanceStmt->execute();
$performances = $performanceStmt->fetchAll(PDO::FETCH_ASSOC);

$markerStmt = $conn->prepare("SELECT marker_id, stage_id, x_coords, y_coords, img, width, types FROM markers ORDER BY marker_id");
$markerStmt->execute();
$markers = $markerStmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include("includes/header.php"); ?>
<div class="cms-content">
    <h5>CMS</h5>
    <hr>
    <?php if ($feedback): ?>
        <div class="panel-message"><?= htmlspecialchars($feedback) ?></div>
    <?php endif; ?>
    <div class="edit-container">
        <div class="buttons-container" id="buttons-container">
            <h6>Bijwerken:</h6>
            <div class="option-button" onclick="showSection('edit-news')">Nieuws & meldingen</div>
            <div class="option-button" onclick="showSection('edit-information')">Festival informatie</div>
            <div class="option-button" onclick="showSection('edit-programma')">Optredingen programma</div>
            <div class="option-button" onclick="showSection('edit-map')">Map</div>
        </div>
    </div>

    <div id="edit-information" class="cms-section">
        <button class="back-btn" onclick="showMenu()">← Terug</button>
        <h3>Festival Informatie</h3>
        <?php foreach ($informationItems as $info): ?>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="save_information">
                <input type="hidden" name="id" value="<?= $info['id'] ?>">
                <label>Titel</label>
                <input type="text" name="titel" value="<?= htmlspecialchars($info['titel']) ?>">
                <label>Informatie</label>
                <textarea name="informatie"><?= htmlspecialchars($info['informatie']) ?></textarea>
                <div class="button-row">
                    <button type="submit">Opslaan</button>
                    <button type="submit" name="action" value="delete_information" class="delete-button">Verwijder</button>
                </div>
            </form>
        <?php endforeach; ?>

        <div class="cms-box">
            <h4>Nieuwe informatie toevoegen</h4>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="add_information">
                <label>Titel</label>
                <input type="text" name="titel" placeholder="Titel">
                <label>Informatie</label>
                <textarea name="informatie" placeholder="Informatie tekst"></textarea>
                <button type="submit">Toevoegen</button>
            </form>
        </div>
    </div>

    <div id="edit-news" class="cms-section">
        <button class="back-btn" onclick="showMenu()">← Terug</button>
        <h3>Nieuws & Meldingen</h3>
        <?php foreach ($newsItems as $item): ?>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="save_news">
                <input type="hidden" name="information_id" value="<?= $item['information_id'] ?>">
                <label>Nieuws</label>
                <textarea name="information"><?= htmlspecialchars($item['information']) ?></textarea>
                <div class="button-row">
                    <button type="submit">Opslaan</button>
                    <button type="submit" name="action" value="delete_news" class="delete-button">Verwijder</button>
                </div>
            </form>
        <?php endforeach; ?>

        <div class="cms-box">
            <h4>Nieuwe melding toevoegen</h4>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="add_news">
                <label>Nieuws</label>
                <textarea name="information" placeholder="Nieuwe melding"></textarea>
                <button type="submit">Toevoegen</button>
            </form>
        </div>
    </div>

    <div id="edit-programma" class="cms-section">
        <button class="back-btn" onclick="showMenu()">← Terug</button>
        <h3>Optredingen programma</h3>
        <?php foreach ($performances as $performance): ?>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="save_performance">
                <input type="hidden" name="performance_id" value="<?= $performance['performance_id'] ?>">
                <label>Dag</label>
                <select name="day">
                    <option value="zaterdag" <?= $performance['day'] === 'zaterdag' ? 'selected' : '' ?>>Zaterdag</option>
                    <option value="zondag" <?= $performance['day'] === 'zondag' ? 'selected' : '' ?>>Zondag</option>
                </select>
                <label>Stage</label>
                <select name="stage">
                    <?php foreach ($stages as $stage): ?>
                        <option value="<?= $stage['id'] ?>" <?= $stage['id'] == $performance['stage'] ? 'selected' : '' ?>><?= htmlspecialchars($stage['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Act</label>
                <select name="artist_id">
                    <?php foreach ($artists as $artist): ?>
                        <option value="<?= $artist['id'] ?>" <?= $artist['id'] == $performance['artist_id'] ? 'selected' : '' ?>><?= htmlspecialchars($artist['naam']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Start</label>
                <input type="time" name="start" value="<?= htmlspecialchars($performance['start']) ?>">
                <label>Eind</label>
                <input type="time" name="end" value="<?= htmlspecialchars($performance['end']) ?>">
                <div class="button-row">
                    <button type="submit">Opslaan</button>
                    <button type="submit" name="action" value="delete_performance" class="delete-button">Verwijder</button>
                </div>
            </form>
        <?php endforeach; ?>

        <div class="cms-box">
            <h4>Nieuwe optreden toevoegen</h4>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="add_performance">
                <label>Dag</label>
                <select name="day">
                    <option value="zaterdag">Zaterdag</option>
                    <option value="zondag">Zondag</option>
                </select>
                <label>Stage</label>
                <select name="stage">
                    <?php foreach ($stages as $stage): ?>
                        <option value="<?= $stage['id'] ?>"><?= htmlspecialchars($stage['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Act</label>
                <select name="artist_id">
                    <?php foreach ($artists as $artist): ?>
                        <option value="<?= $artist['id'] ?>"><?= htmlspecialchars($artist['naam']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Start</label>
                <input type="time" name="start" value="10:00">
                <label>Eind</label>
                <input type="time" name="end" value="11:00">
                <button type="submit">Toevoegen</button>
            </form>
        </div>
    </div>

    <div id="edit-map" class="cms-section">
        <button class="back-btn" onclick="showMenu()">← Terug</button>
        <h3>Festival Map</h3>

        <div class="cms-box">
            <?php foreach ($markers as $marker): ?>
                <form method="POST" class="cms-form">
                    <input type="hidden" name="action" value="save_marker">
                    <input type="hidden" name="marker_id" value="<?= $marker['marker_id'] ?>">
                    <label>Stage</label>
                    <select name="stage_id">
                        <?php foreach ($stages as $stage): ?>
                            <option value="<?= $stage['id'] ?>" <?= $stage['id'] == $marker['stage_id'] ? 'selected' : '' ?>><?= htmlspecialchars($stage['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>X positie</label>
                    <input type="number" name="x_coords" value="<?= htmlspecialchars($marker['x_coords']) ?>">
                    <label>Y positie</label>
                    <input type="number" name="y_coords" value="<?= htmlspecialchars($marker['y_coords']) ?>">
                    <label>Afbeelding URL</label>
                    <input type="text" name="img" value="<?= htmlspecialchars($marker['img']) ?>">
                    <label>Breedte</label>
                    <input type="number" name="width" value="<?= htmlspecialchars($marker['width']) ?>">
                    <label>Type</label>
                    <input type="text" name="types" value="<?= htmlspecialchars($marker['types']) ?>">
                    <div class="button-row">
                        <button type="submit">Opslaan</button>
                        <button type="submit" name="action" value="delete_marker" class="delete-button">Verwijder</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>

        <div class="cms-box">
            <h4>Nieuwe marker toevoegen</h4>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="add_marker">
                <label>Stage</label>
                <select name="stage_id">
                    <?php foreach ($stages as $stage): ?>
                        <option value="<?= $stage['id'] ?>"><?= htmlspecialchars($stage['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>X positie</label>
                <input type="number" name="x_coords" value="0">
                <label>Y positie</label>
                <input type="number" name="y_coords" value="0">
                <label>Afbeelding URL</label>
                <input type="text" name="img" placeholder="assets/path/to/marker.png">
                <label>Breedte</label>
                <input type="number" name="width" value="30">
                <label>Type</label>
                <input type="text" name="types" placeholder="Marker type">
                <button type="submit">Toevoegen</button>
            </form>
        </div>
    </div>
</div>

<script>
    const sections = document.querySelectorAll('.cms-section');
    const menu = document.getElementById('buttons-container');

    function showSection(sectionId) {
        menu.style.display = 'none';
        sections.forEach(section => section.style.display = 'none');
        document.getElementById(sectionId).style.display = 'block';
    }

    function showMenu() {
        sections.forEach(section => section.style.display = 'none');
        menu.style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const active = '<?= addslashes($activeSection) ?>';
        if (active) {
            showSection(active);
        }
    });
</script>
</body>
</html>
