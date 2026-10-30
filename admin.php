<?php

require_once __DIR__ . '/auth.php';

$uploadDir = __DIR__ . '/uploads/';

$folders = [];

if (is_dir($uploadDir)) {
    $items = scandir($uploadDir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $uploadDir . $item;
        if (is_dir($path)) {
            $files = [];
            $dirFiles = scandir($path);
            foreach ($dirFiles as $f) {
                if ($f === '.' || $f === '..') {
                    continue;
                }
                $filePath = $path . '/' . $f;
                if (is_file($filePath)) {
                    $files[] = [
                        'name' => $f,
                        'size' => filesize($filePath),
                        'modified' => date('Y-m-d H:i:s', filemtime($filePath))
                    ];
                }
            }
            usort($files, function ($a, $b) {
                return strcasecmp($a['name'], $b['name']);
            });
            $folders[] = [
                'name' => $item,
                'modified' => date('Y-m-d H:i:s', filemtime($path)),
                'files' => $files
            ];
        }
    }
    usort($folders, function ($a, $b) {
        return strcasecmp($a['name'], $b['name']);
    });
}

function formatBytes($bytes) {
    if ($bytes < 1024) return $bytes . ' B';
    if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
    return round($bytes / 1048576, 1) . ' MB';
}

function isViewable($fileName) {
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'], true);
}

function fileUrl($folder, $file) {
    return 'serve.php?folder=' . urlencode($folder) . '&file=' . urlencode($file);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - File Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin">
    <header class="topbar">
        <div class="topbar-brand">
            <span class="topbar-icon">📁</span>
            <span class="topbar-title">File Manager</span>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>
    <div class="container">
        <h2>Admin - File Manager</h2>
        <div class="card">
            <p style="margin-bottom:16px;color:#555;">
                Total folders: <strong><?= count($folders) ?></strong>
            </p>
            <?php if (isset($_GET['deleted'])): ?>
                <?php
                $msg = [
                    'folder' => 'Folder deleted successfully.',
                    'file' => 'File deleted successfully.'
                ];
                $msgText = $msg[$_GET['deleted']] ?? 'Item deleted successfully.';
                ?>
                <div class="notification success"> <?= htmlspecialchars($msgText) ?> </div>
            <?php endif; ?>
            <?php if (count($folders) === 0): ?>
                <p style="color:#999;">No uploads yet.</p>
            <?php else: ?>
                <div class="folder-list">
                    <?php foreach ($folders as $folder): ?>
                        <div class="folder-item">
                            <div class="folder-header">
                                <span class="folder-icon">📁</span>
                                <span class="folder-name"><?= htmlspecialchars($folder['name']) ?></span>
                                <span class="folder-meta">Updated: <?= $folder['modified'] ?></span>
                                <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Delete folder \'<?= htmlspecialchars($folder['name']) ?>\' and all its contents? This cannot be undone.')">
                                    <input type="hidden" name="type" value="folder">
                                    <input type="hidden" name="folder" value="<?= htmlspecialchars($folder['name']) ?>">
                                    <button type="submit" class="btn-danger">Delete</button>
                                </form>
                            </div>
                            <?php if (count($folder['files']) > 0): ?>
                                <ul class="file-list">
                                    <?php foreach ($folder['files'] as $file): ?>
                                        <li>
                                            <span class="file-name-text"><?= htmlspecialchars($file['name']) ?></span>
                                            <span class="file-size">(<?= formatBytes($file['size']) ?>)</span>
                                            <?php if (isViewable($file['name'])): ?>
                                                <a href="<?= htmlspecialchars(fileUrl($folder['name'], $file['name'])) ?>" target="_blank" class="btn-view btn-sm">View</a>
                                            <?php endif; ?>
                                            <a href="<?= htmlspecialchars(fileUrl($folder['name'], $file['name'])) ?>" download class="btn-download btn-sm">Download</a>
                                            <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Delete file \'<?= htmlspecialchars($file['name']) ?>\'? This cannot be undone.')">
                                                <input type="hidden" name="type" value="file">
                                                <input type="hidden" name="folder" value="<?= htmlspecialchars($folder['name']) ?>">
                                                <input type="hidden" name="file" value="<?= htmlspecialchars($file['name']) ?>">
                                                <button type="submit" class="btn-danger btn-sm">Delete</button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p style="color:#aaa;font-size:13px;margin-top:6px;">No files</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>