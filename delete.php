<?php

require_once __DIR__ . '/auth.php';

$uploadDir = __DIR__ . '/uploads/';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

$type = $_POST['type'] ?? ''; // 'file' or 'folder'
$folder = basename($_POST['folder'] ?? '');
$file = basename($_POST['file'] ?? '');

if ($type === 'folder' && $folder !== '') {
    $target = $uploadDir . $folder;
    if (is_dir($target)) {
        deleteDirectory($target);
    }
    header('Location: admin.php?deleted=folder');
    exit;
}

if ($type === 'file' && $folder !== '' && $file !== '') {
    $target = $uploadDir . $folder . '/' . $file;
    if (is_file($target)) {
        unlink($target);
    }
    header('Location: admin.php?deleted=file');
    exit;
}

header('Location: admin.php');
exit;

function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}