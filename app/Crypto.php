<?php

class Crypto {
    private const METHOD = 'aes-256-cbc';

    /**
     * Encrypts a file using AES-256-CBC.
     *
     * @param string $sourcePath Path to the input file.
     * @param string $destPath Path to save the encrypted file.
     * @param string $key User secret key.
     * @return bool True on success.
     * @throws Exception On IO or Encryption error.
     */
    public static function encryptFile($sourcePath, $destPath, $key) {
        // Read the file content
        $data = file_get_contents($sourcePath);
        if ($data === false) {
            throw new Exception("Gagal membaca file input.");
        }

        // Hash the key to ensure 256-bit length
        $keyHash = hash('sha256', $key, true);

        // Generate a random IV
        $ivLength = openssl_cipher_iv_length(self::METHOD);
        $iv = openssl_random_pseudo_bytes($ivLength);

        // Encrypt the data
        // OPENSSL_RAW_DATA ensures we get binary output, not base64
        $encrypted = openssl_encrypt($data, self::METHOD, $keyHash, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new Exception("Enkripsi gagal: " . openssl_error_string());
        }

        // Combine IV and Encrypted data (IV is needed for decryption)
        $result = $iv . $encrypted;

        // Write to destination
        if (file_put_contents($destPath, $result) === false) {
             throw new Exception("Gagal menulis file output.");
        }

        return true;
    }

    /**
     * Decrypts a file using AES-256-CBC.
     *
     * @param string $sourcePath Path to the encrypted file.
     * @param string $destPath Path to save the decrypted file.
     * @param string $key User secret key.
     * @return bool True on success, False if decryption failed (wrong key).
     * @throws Exception On IO error.
     */
    public static function decryptFile($sourcePath, $destPath, $key) {
        // Read the file content
        $data = file_get_contents($sourcePath);
        if ($data === false) {
             throw new Exception("Gagal membaca file input.");
        }

        $keyHash = hash('sha256', $key, true);
        $ivLength = openssl_cipher_iv_length(self::METHOD);

        // Basic validation: File must be at least as long as IV
        if (strlen($data) < $ivLength) {
            return false; // Invalid file format
        }

        // Extract IV and Ciphertext
        $iv = substr($data, 0, $ivLength);
        $ciphertext = substr($data, $ivLength);

        // Decrypt
        $decrypted = openssl_decrypt($ciphertext, self::METHOD, $keyHash, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            // Decryption failed (likely wrong key)
            return false;
        }

        // Write to destination
        if (file_put_contents($destPath, $decrypted) === false) {
             throw new Exception("Gagal menulis file output.");
        }

        return true;
    }
}
