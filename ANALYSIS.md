# Analisis dan Dokumentasi Aplikasi Kriptografi Audio

## 1. Pendahuluan
Aplikasi ini dirancang untuk mengamankan file audio digital (.wav, .mp3) menggunakan teknik kriptografi simetris. Algoritma yang digunakan adalah **Advanced Encryption Standard (AES)** dengan panjang kunci 256-bit dan mode operasi Cipher Block Chaining (CBC). Aplikasi dikembangkan berbasis web menggunakan PHP untuk kemudahan akses dan antarmuka yang user-friendly.

## 2. Konsep Kriptografi Simetris (AES)
Kriptografi simetris adalah metode enkripsi di mana pengirim dan penerima menggunakan **kunci rahasia yang sama** untuk mengenkripsi dan mendekripsi pesan.
*   **AES-256-CBC**: Standar enkripsi yang diakui secara internasional dan digunakan oleh pemerintah serta industri untuk mengamankan data rahasia.
    *   **256-bit Key**: Kunci enkripsi diturunkan dari password input pengguna menggunakan hashing SHA-256. Ini memastikan kunci memiliki entropi yang cukup dan panjang tepat 32 byte, terlepas dari panjang password asli.
    *   **IV (Initialization Vector)**: Nilai acak 16 byte yang dibangkitkan setiap kali proses enkripsi dilakukan. IV memastikan bahwa dua file identik yang dienkripsi dengan kunci yang sama akan menghasilkan output ciphertext yang berbeda, mencegah serangan pola (*pattern analysis*).
    *   **Padding**: Data audio (plaintext) ditambahkan padding agar total panjangnya menjadi kelipatan blok 16 byte (standar PKCS#7). Padding ini dihapus secara otomatis saat dekripsi.

## 3. Struktur Program
Aplikasi diorganisir dalam struktur direktori berikut:
*   **`app/index.php`** (Frontend):
    *   Menyediakan antarmuka grafis (GUI) berbasis web.
    *   Memiliki form untuk unggah file, input kunci rahasia, dan pilihan mode (Encrypt/Decrypt).
*   **`app/Crypto.php`** (Logic Core):
    *   Berisi kelas `Crypto` dengan metode statis `encryptFile` dan `decryptFile`.
    *   Mengimplementasikan fungsi `openssl_encrypt` dan `openssl_decrypt`.
    *   Menangani pembacaan file biner, pengelolaan IV, dan error handling.
*   **`app/process.php`** (Controller):
    *   Menerima request POST dari frontend.
    *   Memvalidasi input file dan kunci.
    *   Mengelola penyimpanan sementara file di folder `uploads/` dan hasil di `processed/`.
    *   Mengirimkan file hasil kembali ke browser untuk diunduh.

## 4. Alur Proses (Flowchart Deskriptif)

### A. Proses Enkripsi
1.  **Input**: Pengguna memilih file audio asli dan memasukkan Secret Key.
2.  **Hashing**: Sistem mengonversi Secret Key menjadi hash SHA-256 (32 byte).
3.  **IV Generation**: Sistem membangkitkan 16 byte data acak sebagai IV.
4.  **Enkripsi**: Data audio dienkripsi menggunakan algoritma AES-256-CBC dengan Key dan IV.
5.  **Pengemasan**: IV disisipkan di awal file output (`[IV 16 byte] + [Ciphertext]`).
6.  **Output**: File terenkripsi (`.enc`) dihasilkan dan diunduh pengguna. File ini tidak dapat diputar (unplayable).

### B. Proses Dekripsi
1.  **Input**: Pengguna mengunggah file `.enc` dan memasukkan Secret Key.
2.  **Hashing**: Sistem mengonversi Secret Key menjadi hash SHA-256.
3.  **Ekstraksi**: Sistem membaca 16 byte pertama file sebagai IV dan sisanya sebagai Ciphertext.
4.  **Dekripsi**: Ciphertext didekripsi menggunakan AES-256-CBC dengan Key dan IV yang diekstrak.
5.  **Validasi**:
    *   Jika kunci benar, padding valid akan terdeteksi dan dihapus.
    *   Jika kunci salah, fungsi dekripsi akan mengembalikan `false` (kegagalan).
6.  **Output**: Jika berhasil, file audio asli dikembalikan dan dapat diputar dengan kualitas sempurna.

## 5. Analisis Hasil dan Pengujian

### Keberhasilan Dekripsi (Integritas Data)
Berdasarkan pengujian algoritma (simulasi `verify_algo.py`), proses dekripsi mampu mengembalikan data asli secara sempurna (*lossless*).
*   File Audio Asli vs File Hasil Dekripsi: **Identik (Bit-exact)**.
*   Kualitas Suara: Tidak ada distorsi atau penurunan kualitas karena operasi dilakukan pada level byte stream, bukan re-encoding audio.

### Analisis Ukuran File
Proses enkripsi menyebabkan sedikit penambahan ukuran file (*overhead*):
*   **Overhead IV**: Tetap 16 byte.
*   **Overhead Padding**: Antara 1 hingga 16 byte (rata-rata 8 byte).
*   **Total Pertambahan**: Maksimum 32 byte.
*   **Kesimpulan**: Pertambahan ukuran sangat tidak signifikan (< 0.01%) dibandingkan ukuran rata-rata file audio (3-10 MB).

### Analisis Keamanan
*   **Kerahasiaan**: File audio terenkripsi berisi data acak semu. Header file audio (seperti "RIFF" untuk WAV atau "ID3" untuk MP3) ikut terenkripsi, sehingga software pemutar media tidak akan mengenali format file tersebut.
*   **Integritas Kunci**: Dengan AES-256, serangan *Brute Force* membutuhkan waktu komputasi yang tidak realistis dengan teknologi saat ini. Keamanan sistem bergantung sepenuhnya pada kerahasiaan Secret Key yang dimiliki pengguna.

## 6. Kesimpulan
Aplikasi ini berhasil mengimplementasikan pengamanan file audio menggunakan standar industri AES-256. Mekanisme validasi kunci dan penggunaan IV yang unik per file menjamin keamanan data terhadap akses tidak sah dan serangan pola, memenuhi persyaratan untuk menjaga kerahasiaan informasi suara digital.
