<?php
$mapsDir = __DIR__ . '/maps';
$gpxFiles = [];

if (is_dir($mapsDir)) {
    foreach (scandir($mapsDir) as $file) {
        $path = $mapsDir . '/' . $file;
        if (is_file($path) && preg_match('/\.gpx$/i', $file)) {
            $gpxFiles[] = $file;
        }
    }
    natcasesort($gpxFiles);
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
if (!preg_match('/^[A-Za-z0-9.-]+(?::\d{1,5})?$/', $host)) {
    $host = 'localhost';
}
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike maps</title>
</head>
<body>
    <h1>Bike maps</h1>

    <?php if (empty($gpxFiles)): ?>
        <p>No GPX files found in <code>maps/</code>.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($gpxFiles as $file): ?>
                <?php
                $filePath = $basePath . '/maps/' . rawurlencode($file);
                $fileUrl = $scheme . '://' . $host . $filePath;
                $state = rawurlencode(json_encode(['urls' => [$fileUrl]]));
                ?>
                <li>
                    <a href="https://gpx.studio/?state=<?= $state ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($file, ENT_QUOTES, 'UTF-8') ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
