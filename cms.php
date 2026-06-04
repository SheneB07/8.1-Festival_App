<?php
include("connection.php");

$feedback = '';
$activeSection = $_GET['section'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action'])) {
    try {
        switch ($_POST['action']) {
            case 'add_information':
                $stmt = $conn->prepare("INSERT INTO informatie (titel, informatie, titel_en, informatie_en) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['titel'] ?? '',
                    $_POST['informatie'] ?? '',
                    $_POST['titel_en'] ?? '',
                    $_POST['informatie_en'] ?? ''
                ]);
                header('Location: cms.php?section=edit-information');
                exit;

            case 'save_information':
                $stmt = $conn->prepare("UPDATE informatie SET titel = ?, informatie = ?, titel_en = ?, informatie_en = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['titel'] ?? '',
                    $_POST['informatie'] ?? '',
                    $_POST['titel_en'] ?? '',
                    $_POST['informatie_en'] ?? '',
                ]);
                header('Location: cms.php?section=edit-information');
                exit;

            case 'delete_information':
                $stmt = $conn->prepare("DELETE FROM informatie WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                header('Location: cms.php?section=edit-information');
                exit;

            case 'add_news':
                $stmt = $conn->prepare("INSERT INTO nieuws_meldingen (information, en_information) VALUES (?, ?)");
                $stmt->execute([
                    $_POST['information'] ?? '',
                    $_POST['en_information'] ?? ''
                ]);
                header('Location: cms.php?section=edit-news');
                exit;

            case 'save_news':
                $stmt = $conn->prepare("UPDATE nieuws_meldingen SET information = ?, en_information = ? WHERE information_id = ?");
                $stmt->execute([
                    $_POST['information'] ?? '',
                    $_POST['en_information'] ?? '',
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

            case 'add_artist':
                $stmt = $conn->prepare("INSERT INTO artists (naam, naam_en, omschrijving, omschrijving_en, afbeelding, video, tekst, tekst_en) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['naam'] ?? '',
                    $_POST['naam_en'] ?? '',
                    $_POST['omschrijving'] ?? '',
                    $_POST['omschrijving_en'] ?? '',
                    $_POST['afbeelding'] ?? '',
                    $_POST['video'] ?? '',
                    $_POST['tekst'] ?? '',
                    $_POST['tekst_en'] ?? ''
                ]);
                header('Location: cms.php?section=edit-artists');
                exit;

            case 'save_artist':
                $stmt = $conn->prepare("UPDATE artists SET naam = ?, naam_en = ?, omschrijving = ?, omschrijving_en = ?, afbeelding = ?, video = ?, tekst = ?, tekst_en = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['naam'] ?? '',
                    $_POST['naam_en'] ?? '',
                    $_POST['omschrijving'] ?? '',
                    $_POST['omschrijving_en'] ?? '',
                    $_POST['afbeelding'] ?? '',
                    $_POST['video'] ?? '',
                    $_POST['tekst'] ?? '',
                    $_POST['tekst_en'] ?? '',
                    $_POST['id']
                ]);
                header('Location: cms.php?section=edit-artists');
                exit;

            case 'delete_artist':
                $stmt = $conn->prepare("DELETE FROM artists WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                header('Location: cms.php?section=edit-artists');
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

$informationStmt = $conn->prepare("SELECT id, titel, informatie, titel_en, informatie_en FROM informatie ORDER BY id");
$informationStmt->execute();
$informationItems = $informationStmt->fetchAll(PDO::FETCH_ASSOC);

$newsStmt = $conn->prepare("SELECT information_id, information, en_information FROM nieuws_meldingen ORDER BY information_id");
$newsStmt->execute();
$newsItems = $newsStmt->fetchAll(PDO::FETCH_ASSOC);

$artistStmt = $conn->prepare("SELECT id, naam, naam_en, omschrijving, omschrijving_en, afbeelding, video, tekst, tekst_en FROM artists ORDER BY naam");
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
    <title>Festival CMS - Admin</title>
    <meta name="description" content="Festival content management system">
    <link rel="stylesheet" href="style.css">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#0a74da">
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
            <div class="option-button" onclick="showSection('edit-artists')">Artiesten</div>
            <div class="option-button" onclick="showSection('edit-map')">Map</div>
        </div>
    </div>

    <div id="edit-information" class="cms-section">
        <button class="back-btn" onclick="showMenu()">← Terug</button>
        <h3>Festival Informatie</h3>
        <div class="cms-box cms-add">
            <h4>Nieuwe informatie toevoegen</h4>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="add_information">
                <label>Titel</label>
                <input type="text" name="titel" placeholder="Titel">
                <label>Informatie</label>
                <textarea name="informatie" placeholder="Informatie tekst"></textarea>
                <label>Informatie (EN)</label>
                <textarea name="informatie_en" placeholder="Information English"></textarea>
                <button type="submit">Toevoegen</button>
            </form>
        </div>
        <?php foreach ($informationItems as $info): ?>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="save_information">
                <input type="hidden" name="id" value="<?= $info['id'] ?>">
                <label>Titel</label>
                <input type="text" name="titel" value="<?= htmlspecialchars($info['titel']) ?>">
                <label>Informatie</label>
                <textarea name="informatie"><?= htmlspecialchars($info['informatie']) ?></textarea>
                <label>Informatie (EN)</label>
                <textarea name="informatie_en"><?= htmlspecialchars($info['informatie_en']) ?></textarea>
                <div class="button-row">
                    <button type="submit">Opslaan</button>
                    <button type="submit" name="action" value="delete_information" class="delete-button">Verwijder</button>
                </div>
            </form>
        <?php endforeach; ?>
    </div>

    <div id="edit-news" class="cms-section">
        <button class="back-btn" onclick="showMenu()">← Terug</button>
        <h3>Nieuws & Meldingen</h3>
        <div class="cms-box cms-add">
            <h4>Nieuwe melding toevoegen</h4>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="add_news">
                <label>Nieuws</label>
                <textarea name="information" placeholder="Nieuwe melding"></textarea>
                <label>Nieuws (EN)</label>
                <textarea name="en_information" placeholder="News English"></textarea>
                <button type="submit">Toevoegen</button>
            </form>
        </div>
        <?php foreach ($newsItems as $item): ?>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="save_news">
                <input type="hidden" name="information_id" value="<?= $item['information_id'] ?>">
                <label>Nieuws</label>
                <textarea name="information"><?= htmlspecialchars($item['information']) ?></textarea>
                <label>Nieuws (EN)</label>
                <textarea name="en_information"><?= htmlspecialchars($item['en_information']) ?></textarea>
                <div class="button-row">
                    <button type="submit">Opslaan</button>
                    <button type="submit" name="action" value="delete_news" class="delete-button">Verwijder</button>
                </div>
            </form>
        <?php endforeach; ?>
    </div>

    <div id="edit-programma" class="cms-section">
        <button class="back-btn" onclick="showMenu()">← Terug</button>
        <h3>Optredingen programma</h3>
        <div class="cms-box cms-add">
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
    </div>

    <div id="edit-artists" class="cms-section">
        <button class="back-btn" onclick="showMenu()">← Terug</button>
        <h3>Artiesten</h3>
        <div class="cms-box cms-add">
            <h4>Nieuwe artiest toevoegen</h4>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="add_artist">
                <label>Naam (NL)</label>
                <input type="text" name="naam" placeholder="Naam Nederlands">
                <label>Naam (EN)</label>
                <input type="text" name="naam_en" placeholder="Name English">
                <label>Omschrijving (NL)</label>
                <textarea name="omschrijving" placeholder="Omschrijving Nederlands"></textarea>
                <label>Omschrijving (EN)</label>
                <textarea name="omschrijving_en" placeholder="Description English"></textarea>
                <label>Informatie (NL)</label>
                <textarea name="tekst" placeholder="Informatie Nederlands"></textarea>
                <label>Informatie (EN)</label>
                <textarea name="tekst_en" placeholder="Information English"></textarea>
                <label>Afbeelding URL</label>
                <input type="text" name="afbeelding" placeholder="assets/path/to/image.jpg">
                <label>Video URL</label>
                <input type="text" name="video" placeholder="Video URL">
                <button type="submit">Toevoegen</button>
            </form>
        </div>
        <div class="cms-box">
            <?php foreach ($artists as $artist): ?>
                <form method="POST" class="cms-form">
                    <input type="hidden" name="action" value="save_artist">
                    <input type="hidden" name="id" value="<?= $artist['id'] ?>">
                    <label>Naam (NL)</label>
                    <input type="text" name="naam" value="<?= htmlspecialchars($artist['naam']) ?>">
                    <label>Naam (EN)</label>
                    <input type="text" name="naam_en" value="<?= htmlspecialchars($artist['naam_en']) ?>">
                    <label>Omschrijving (NL)</label>
                    <textarea name="omschrijving"><?= htmlspecialchars($artist['omschrijving']) ?></textarea>
                    <label>Omschrijving (EN)</label>
                    <textarea name="omschrijving_en"><?= htmlspecialchars($artist['omschrijving_en']) ?></textarea>
                    <label>Informatie (NL)</label>
                    <textarea name="tekst"><?= htmlspecialchars($artist['tekst']) ?></textarea>
                    <label>Informatie (EN)</label>
                    <textarea name="tekst_en"><?= htmlspecialchars($artist['tekst_en']) ?></textarea>
                    <label>Afbeelding URL</label>
                    <input type="text" name="afbeelding" value="<?= htmlspecialchars($artist['afbeelding']) ?>">
                    <label>Video URL</label>
                    <input type="text" name="video" value="<?= htmlspecialchars($artist['video']) ?>">
                    <div class="button-row">
                        <button type="submit">Opslaan</button>
                        <button type="submit" name="action" value="delete_artist" class="delete-button">Verwijder</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="edit-map" class="cms-section">
        <button class="back-btn" onclick="showMenu()">← Terug</button>
        <h3>Festival Map</h3>

        <div class="cms-box cms-add">
            <h4>Nieuwe marker toevoegen</h4>
            <form method="POST" class="cms-form">
                <input type="hidden" name="action" value="add_marker">
                <label>Stage</label>
                <select name="stage_id">
                    <?php foreach ($stages as $stage): ?>
                        <option value="<?= $stage['id'] ?>"><?= htmlspecialchars($stage['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Klik op de kaart voor X/Y</label>
                <div class="marker-picker">
                    <div class="marker-map" id="marker-map">
                        <img src="assets/map/map.svg" id="marker-map-img" alt="Festival map" />
                        <div class="marker-preview" id="marker-preview"></div>
                    </div>
                    <p>Klik ergens op de kaart om de X- en Y-coördinaten automatisch in te vullen.</p>
                </div>
                <label>X positie</label>
                <input type="number" id="marker-x" name="x_coords" value="0">
                <label>Y positie</label>
                <input type="number" id="marker-y" name="y_coords" value="0">
                <label>Afbeelding URL</label>
                <input type="text" name="img" placeholder="assets/path/to/marker.png">
                <label>Breedte</label>
                <input type="number" name="width" value="30">
                <label>Type</label>
                <input type="text" name="types" placeholder="Marker type">
                <button type="submit">Toevoegen</button>
            </form>
        </div>
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

        const markerImage = document.getElementById('marker-map-img');
        const markerPreview = document.getElementById('marker-preview');
        const xInput = document.getElementById('marker-x');
        const yInput = document.getElementById('marker-y');

        if (markerImage && markerPreview && xInput && yInput) {
            markerImage.addEventListener('click', function (event) {
                const rect = markerImage.getBoundingClientRect();
                const x = Math.round(event.clientX - rect.left);
                const y = Math.round(event.clientY - rect.top);
                xInput.value = x;
                yInput.value = y;
                markerPreview.style.left = x + 'px';
                markerPreview.style.top = y + 'px';
                markerPreview.style.display = 'block';
            });
        }
    });
</script>
</body>
</html>
