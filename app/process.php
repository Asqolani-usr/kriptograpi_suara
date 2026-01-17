<?php
require_once 'Crypto.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Metode permintaan tidak valid.");
}

// Check for file upload errors
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    die("Gagal mengunggah file. Kode error: " . ($_FILES['file']['error'] ?? 'Unknown'));
}

$file = $_FILES['file'];
$key = $_POST['key'] ?? '';
$action = $_POST['action'] ?? 'encrypt';

if (empty($key)) {
    die("Kunci Rahasia wajib diisi.");
}

$uploadDir = __DIR__ . '/uploads/';
$processedDir = __DIR__ . '/processed/';

// Ensure directories exist
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
if (!is_dir($processedDir)) mkdir($processedDir, 0777, true);

// Move uploaded file to safe location
$sourcePath = $uploadDir . basename($file['name']);
if (!move_uploaded_file($file['tmp_name'], $sourcePath)) {
    die("Gagal memindahkan file yang diunggah.");
}

try {
    if ($action === 'encrypt') {
        $destFilename = 'encrypted_' . basename($file['name']) . '.enc';
        $destPath = $processedDir . $destFilename;

        Crypto::encryptFile($sourcePath, $destPath, $key);

        // Force Download
        if (file_exists($destPath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $destFilename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($destPath));
            readfile($destPath);
            exit;
        } else {
            die("Error: File output tidak ditemukan.");
        }

    } elseif ($action === 'decrypt') {
        $originalName = basename($file['name']);
        // Intelligent naming: remove .enc if exists
        if (substr($originalName, -4) === '.enc') {
            $destFilename = 'decrypted_' . substr($originalName, 0, -4);
        } else {
            $destFilename = 'decrypted_' . $originalName;
        }

        $destPath = $processedDir . $destFilename;

        $success = Crypto::decryptFile($sourcePath, $destPath, $key);

        if ($success) {
            // Force Download
            if (file_exists($destPath)) {
                header('Content-Description: File Transfer');
                // We default to octet-stream to ensure download, though audio/mpeg or audio/wav is correct
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $destFilename . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($destPath));
                readfile($destPath);
                exit;
            } else {
                die("Error: File output tidak ditemukan.");
            }
        } else {
            // Standard Academic/Security practice: Don't give too much detail, but here we say key is wrong.
            echo "<h3 style='color:red; text-align:center;'>Dekripsi Gagal!</h3>";
            echo "<p style='text-align:center;'>Kunci rahasia yang dimasukkan tidak sesuai dengan yang digunakan untuk enkripsi, atau file rusak.</p>";
            echo "<p style='text-align:center;'><a href='index.php'>Kembali</a></p>";
        }
    } else {
        die("Aksi yang dipilih tidak valid.");
    }
} catch (Exception $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
