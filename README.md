# Aplikasi Enkripsi Audio (PHP + AES-256)

Aplikasi berbasis web sederhana untuk mengenkripsi dan mendekripsi file audio (.wav, .mp3) menggunakan algoritma kriptografi AES-256-CBC. Proyek ini dibuat untuk tujuan edukasi dan penelitian skripsi.

## Prasyarat (Requirements)
Agar aplikasi dapat berjalan, pastikan sistem Anda memiliki:
1.  **PHP** (Versi 7.4 atau lebih baru).
2.  Ekstensi **OpenSSL** pada PHP (Biasanya sudah aktif secara default).
3.  Browser web modern (Chrome, Firefox, Edge).

## Cara Menjalankan Aplikasi

Aplikasi ini tidak memerlukan konfigurasi server yang rumit (seperti Apache/Nginx) dan dapat dijalankan langsung menggunakan **PHP Built-in Server**.

1.  **Buka Terminal / Command Prompt**
    Buka terminal di komputer Anda.

2.  **Masuk ke Direktori Proyek**
    Gunakan perintah `cd` untuk masuk ke folder tempat Anda menyimpan kode ini.
    Contoh:
    ```bash
    cd /path/to/folder/ini
    ```

3.  **Jalankan Server PHP**
    Jalankan perintah berikut:
    ```bash
    php -S localhost:8000 -t app
    ```
    *   `-S localhost:8000`: Menjalankan server pada port 8000.
    *   `-t app`: Menentukan folder `app` sebagai root direktori server (karena file index.php ada di dalam folder `app`).

4.  **Akses Aplikasi**
    Buka browser dan kunjungi alamat:
    [http://localhost:8000](http://localhost:8000)

## Cara Menggunakan

### 1. Enkripsi (Mengamankan File)
1.  Pada halaman utama, klik **"Choose File"** dan pilih file audio (.mp3 atau .wav) dari komputer Anda.
2.  Pada kolom **"Kunci Rahasia"**, masukkan password yang ingin Anda gunakan. **Ingat password ini!** Tanpa password ini, file tidak bisa dikembalikan.
3.  Pastikan pilihan **"Enkripsi"** terpilih.
4.  Klik tombol **"Proses"**.
5.  File hasil enkripsi (berakhiran `.enc`) akan otomatis terunduh.
    > **Catatan:** File `.enc` ini adalah file audio yang sudah diacak secara matematis. Anda **TIDAK BISA** membukanya langsung dengan pemutar musik (seperti VLC, Media Player, dll) karena formatnya sudah bukan audio lagi. Untuk mendengarkannya kembali, Anda wajib melakukan proses **Dekripsi** di bawah ini.

### 2. Dekripsi (Mengembalikan File)
1.  Klik **"Choose File"** dan pilih file yang sudah dienkripsi sebelumnya (file `.enc`).
2.  Masukkan **"Kunci Rahasia"** yang **SAMA PERSIS** dengan yang digunakan saat enkripsi.
3.  Pilih opsi **"Dekripsi"**.
4.  Klik tombol **"Proses"**.
5.  Jika sandi benar, file audio asli akan terunduh dan dapat diputar kembali.
6.  Jika sandi salah, sistem akan menampilkan pesan error.

## Struktur Folder
*   `app/`: Berisi kode sumber aplikasi.
    *   `index.php`: Halaman antarmuka pengguna.
    *   `process.php`: Logika pemrosesan form.
    *   `Crypto.php`: Logika inti kriptografi (AES-256).
    *   `uploads/`: Folder sementara untuk file yang diunggah.
    *   `processed/`: Folder sementara untuk hasil enkripsi/dekripsi.
*   `ANALYSIS.md`: Penjelasan teknis, flowchart, dan analisis keamanan.
*   `verify_algo.py`: Script Python independen untuk memverifikasi logika algoritma. Script ini mensimulasikan proses enkripsi dan dekripsi yang dilakukan oleh aplikasi PHP (AES-256-CBC, Hashing Kunci, dan penanganan IV) untuk memastikan bahwa logika matematika yang digunakan benar dan hasilnya dapat dikembalikan ke bentuk semula (*reversible*). Ini berguna sebagai bukti validasi (proof of concept) untuk pengujian.
