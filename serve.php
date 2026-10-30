<?php

require_once __DIR__ . '/auth.php';

$uploadDir = __DIR__ . '/uploads/';

$folder = basename($_GET['folder'] ?? '');
$file = basename($_GET['file'] ?? '');

if ($folder === '' || $file === '') {
    http_response_code(400);
    die('Invalid request.');
}

$filePath = $uploadDir . $folder . '/' . $file;

if (!file_exists($filePath) || !is_file($filePath)) {
    http_response_code(404);
    die('File not found.');
}

// Determine MIME type
$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$mimeTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'pdf' => 'application/pdf',
    'txt' => 'text/plain',
    'csv' => 'text/csv',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xls' => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
];

$mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

// For inline viewable types, allow browser to display; otherwise force download
$inlineTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf', 'text/plain', 'text/csv'];
$disposition = in_array($mimeType, $inlineTypes, true) ? 'inline' : 'attachment';

header('Content-Type: ' . $mimeType);
header('Content-Disposition: ' . $disposition . '; filename="' . $file . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: no-cache, must-revalidate');

readfile($filePath);
exit;