# LAPORAN IMPLEMENTASI KRIPTOGRAFI SIMETRIS PADA FILE AUDIO MENGGUNAKAN ALGORITMA AES-256

## 1. PENDAHULUAN

### 1.1 Latar Belakang
Di era digital saat ini, pertukaran informasi multimedia, termasuk file audio, semakin masif. Seringkali, file audio tersebut berisi informasi sensitif atau rahasia, seperti rekaman rapat tertutup, bukti percakapan, atau karya seni yang hak ciptanya perlu dilindungi. Tanpa mekanisme keamanan yang memadai, file-file ini rentan terhadap penyadapan atau akses oleh pihak yang tidak berwenang. Kriptografi menawarkan solusi untuk melindungi kerahasiaan data tersebut dengan mengubahnya menjadi bentuk yang tidak dapat dipahami (ciphertext).

### 1.2 Tujuan
Tujuan dari pembuatan aplikasi ini adalah:
1.  Membangun sistem pengamanan file audio digital (.wav dan .mp3).
2.  Menerapkan algoritma kriptografi simetris *Advanced Encryption Standard* (AES) dengan panjang kunci 256-bit.
3.  Menyediakan antarmuka yang mudah digunakan untuk proses enkripsi dan dekripsi.

## 2. LANDASAN TEORI

### 2.1 Kriptografi Simetris
Kriptografi simetris adalah teknik enkripsi di mana kunci yang digunakan untuk enkripsi adalah sama dengan kunci untuk dekripsi. Kecepatan komputasi yang tinggi menjadikan metode ini ideal untuk mengamankan data berukuran besar seperti file multimedia.

### 2.2 Advanced Encryption Standard (AES)
AES adalah standar enkripsi yang ditetapkan oleh NIST (*National Institute of Standards and Technology*). AES merupakan *block cipher* yang beroperasi pada blok data 128-bit. Dalam aplikasi ini, digunakan varian AES-256 yang menggunakan kunci sepanjang 256-bit, memberikan tingkat keamanan yang sangat tinggi terhadap serangan *brute force*.

### 2.3 Mode Operasi CBC (*Cipher Block Chaining*)
Aplikasi menggunakan mode CBC, di mana setiap blok plaintext di-XOR dengan blok ciphertext sebelumnya sebelum dienkripsi. Untuk blok pertama, digunakan *Initialization Vector* (IV) yang acak. Ini memastikan bahwa input plaintext yang sama tidak akan menghasilkan ciphertext yang sama, mencegah analisis pola.

## 3. METODOLOGI PENELITIAN

### 3.1 Perancangan Sistem
Aplikasi dikembangkan berbasis web menggunakan bahasa pemrograman PHP. Arsitektur sistem dibagi menjadi tiga bagian utama:
1.  **Antarmuka Pengguna (Frontend)**: Form HTML untuk unggah file dan input kunci.
2.  **Logika Kriptografi (Backend)**: Modul PHP yang menangani algoritma AES-256-CBC.
3.  **Manajemen File**: Sistem penyimpanan sementara untuk file asli dan hasil proses.

### 3.2 Alur Proses (Flowchart)
1.  **Enkripsi**:
    *   Pengguna mengunggah file audio dan memasukkan kata sandi.
    *   Sistem menghasilkan hash SHA-256 dari kata sandi sebagai kunci enkripsi (Key).
    *   Sistem membangkitkan 16-byte acak sebagai IV.
    *   File audio dibaca sebagai *byte stream* dan dienkripsi.
    *   Hasil (IV + Ciphertext) disimpan sebagai file `.enc`.
2.  **Dekripsi**:
    *   Pengguna mengunggah file `.enc` dan memasukkan kata sandi.
    *   Sistem mengekstrak IV dari 16 byte pertama file.
    *   Sisa data (Ciphertext) didekripsi menggunakan Key dan IV tersebut.
    *   Jika berhasil, file audio asli dikembalikan.

## 4. HASIL DAN PEMBAHASAN

### 4.1 Implementasi Antarmuka
Aplikasi berhasil dibangun dengan antarmuka web sederhana yang memiliki fitur pemilihan file, input kunci rahasia, dan pilihan aksi (Enkripsi/Dekripsi).

### 4.2 Pengujian Fungsional
Pengujian dilakukan dengan skenario sebagai berikut:

| No | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Kesimpulan |
|----|-------------------|-----------------------|-----------------|------------|
| 1  | Enkripsi file .mp3 dengan kunci "rahasia" | File terunduh dengan ekstensi .enc | Berhasil | Valid |
| 2  | Memutar file hasil enkripsi (.enc) | Media player gagal memutar file | File tidak dikenali player | Valid |
| 3  | Dekripsi file .enc dengan kunci "rahasia" | File audio asli kembali dan dapat diputar | Berhasil | Valid |
| 4  | Dekripsi file .enc dengan kunci "salah" | Sistem menolak dan menampilkan pesan error | Pesan "Dekripsi Gagal" muncul | Valid |

### 4.3 Analisis Keamanan dan Kinerja
*   **Kerahasiaan**: Penggunaan IV acak menjamin bahwa enkripsi file yang sama berulang kali menghasilkan output berbeda. Header file audio ikut terenkripsi, menyembunyikan format asli file.
*   **Integritas**: Mekanisme padding pada AES-CBC secara implisit berfungsi sebagai pengecekan integritas kunci. Jika kunci salah, padding saat dekripsi akan tidak valid.
*   **Ukuran File**: Proses enkripsi hanya menambah ukuran file sebesar maksimal 32 byte (16 byte IV + maksimal 16 byte padding). Overhead ini sangat kecil (< 0.01%) untuk file audio.

## 5. KESIMPULAN

Aplikasi kriptografi audio ini berhasil menerapkan algoritma AES-256-CBC untuk mengamankan file suara. Sistem mampu menjaga kerahasiaan data dengan mengubah file audio menjadi format yang tidak dapat diakses tanpa kunci yang benar. Validasi kunci berfungsi dengan baik untuk mencegah akses tidak sah. Implementasi ini layak digunakan sebagai dasar pengamanan aset digital audio.

## 6. DAFTAR PUSTAKA
1.  NIST. (2001). *Advanced Encryption Standard (AES)*. FIPS PUB 197.
2.  Katz, J., & Lindell, Y. (2014). *Introduction to Modern Cryptography*. CRC Press.
3.  Dokumentasi Resmi PHP OpenSSL. https://www.php.net/manual/en/book.openssl.php
