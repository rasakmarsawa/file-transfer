<?php

$uploadDir = __DIR__ . '/uploads/';

$maxFileSize = 5 * 1024 * 1024; // 5 MB per file

$allowedExtensions = [
    'jpg',
    'jpeg',
    'png',
    'pdf',
    'doc',
    'docx',
    'xls',
    'xlsx',
    'csv'
];

// Validasi request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method.');
}

// Validasi file
if (!isset($_FILES['files'])) {
    die('No files uploaded.');
}

$files = $_FILES['files'];

if (!is_array($files['name']) || count($files['name']) === 0) {
    die('No files selected.');
}

// Buat folder utama jika belum tersedia
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate nama folder unik untuk setiap proses upload
$folderName = date('Ymd_His') . '_' . bin2hex(random_bytes(3));

$targetDir = $uploadDir . $folderName . '/';

// Buat folder baru
if (!mkdir($targetDir, 0755, true)) {
    die('Failed to create upload directory.');
}

$successFiles = [];
$failedFiles = [];

for ($i = 0; $i < count($files['name']); $i++) {

    $originalName = basename($files['name'][$i]);
    $tmpName = $files['tmp_name'][$i];
    $fileSize = $files['size'][$i];
    $fileError = $files['error'][$i];

    // Validasi error upload
    if ($fileError !== UPLOAD_ERR_OK) {
        $failedFiles[] = $originalName . ' - Upload error: ' . $fileError;
        continue;
    }

    // Validasi ukuran
    if ($fileSize > $maxFileSize) {
        $failedFiles[] = $originalName . ' - File exceeds 5 MB.';
        continue;
    }

    // Ambil extension
    $extension = strtolower(
        pathinfo($originalName, PATHINFO_EXTENSION)
    );

    // Validasi extension
    if (!in_array($extension, $allowedExtensions, true)) {
        $failedFiles[] = $originalName . ' - File type not allowed.';
        continue;
    }

    // Sanitasi nama file asli
    $safeName = preg_replace(
        '/[^a-zA-Z0-9._-]/',
        '_',
        $originalName
    );

    // Format nama file: {nama_folder}_{nama_file_asli}
    $storedName = $folderName . '_' . $safeName;

    $destination = $targetDir . $storedName;

    // Pindahkan file
    if (move_uploaded_file($tmpName, $destination)) {

        $successFiles[] = $storedName;

    } else {

        $failedFiles[] = $originalName . ' - Failed to save file.';

    }
}

// Tentukan status result
if (count($failedFiles) === 0) {
    $result = 'success';
} elseif (count($successFiles) === 0) {
    $result = 'fail';
} else {
    $result = 'partial';
}

// Redirect back to upload page dengan result value di URL
$redirectUrl = 'index.html?result=' . $result
    . '&success=' . count($successFiles)
    . '&failed=' . count($failedFiles)
    . '&folder=' . urlencode($folderName);

header('Location: ' . $redirectUrl);
exit;