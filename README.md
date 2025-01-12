
# Sipro PLN - Sistem Informasi Proyek PLN

**Sipro PLN** adalah aplikasi manajemen proyek yang dirancang untuk mendukung kelancaran dan efisiensi dalam pengelolaan proyek-proyek di lingkungan PLN. Aplikasi ini memiliki berbagai fitur yang memudahkan pengawasan, pemantauan, dan laporan terkait proyek yang sedang berlangsung. Dengan Sipro PLN, tim pengawas dan manajer proyek dapat bekerja lebih efektif dan mendapatkan informasi yang akurat serta up-to-date.

## Fitur Utama

### 1. **Manajemen Proyek**

-   Menyediakan fitur untuk membuat, mengelola, dan memantau proyek-proyek PLN.
-   Menyimpan data terkait proyek seperti nama proyek, status proyek, tanggal mulai, tanggal akhir, dan detail lainnya.
-   Membantu tim manajerial dalam memonitor kemajuan proyek.

### 2. **Manajemen Pengawas**

-   Pengawas proyek dapat mendaftar akun secara mandiri melalui aplikasi.
-   Setiap pengawas dapat mengelola informasi profil mereka, termasuk data pribadi dan riwayat proyek yang mereka awasi.
-   Administrator dapat mengelola dan mengatur hak akses pengawas terhadap proyek tertentu.

### 3. **Manajemen Cuti**

-   Fitur ini memungkinkan pengawas untuk mengajukan permohonan cuti secara mandiri.
-   Administrator dapat menyetujui atau menolak permohonan cuti dan mengelola data cuti yang diajukan.
-   Pengawas dapat melihat sisa cuti yang tersedia dan status pengajuan cuti mereka.

### 4. **Laporan Harian**

-   Setiap pengawas dapat membuat laporan harian terkait pekerjaan yang telah dilakukan di proyek.
-   Laporan harian mencakup informasi tentang pekerjaan yang telah diselesaikan, cuaca pada hari tersebut, material yang digunakan, dan jumlah pekerja yang terlibat.
-   Laporan ini dapat digunakan untuk memantau perkembangan proyek serta kendala yang mungkin dihadapi.

### 5. **Laporan Permasalahan Proyek**

-   Pengawas dapat melaporkan masalah yang dihadapi dalam proyek kepada vendor atau pihak terkait.
-   Laporan masalah ini akan diteruskan ke direksi untuk diketahui dan ditindaklanjuti.
-   Sistem ini memungkinkan pemantauan status penyelesaian masalah yang dilaporkan.

### 6. **Galeri Foto**

-   Galeri foto berfungsi untuk menyimpan foto-foto terkait pekerjaan proyek.
-   Pengawas dan tim proyek dapat mengunggah foto dari lokasi proyek untuk dokumentasi atau sebagai bukti visual kemajuan pekerjaan.
-   Foto-foto ini dapat dilihat oleh pihak yang berwenang, termasuk manajer proyek dan direksi.

### 7. **Kehadiran Pengawas**

-   Setiap pengawas dapat melakukan check-in dan check-out untuk mencatat kehadiran mereka di lokasi proyek.
-   Sistem ini memantau waktu kerja pengawas secara otomatis dan memberikan laporan kehadiran yang akurat.

### 8. **Rekapitulasi Hari Kerja Pengawas**

-   Aplikasi menyediakan rekapitulasi harian yang mencatat kehadiran dan laporan yang dibuat oleh pengawas pada hari tersebut.
-   Sistem ini memberikan informasi detail tentang total jam kerja pengawas, laporan yang dibuat, serta status cuti yang mungkin dimiliki pengawas.

### 9. **Laporan-Laporan**

-   Aplikasi menyediakan berbagai jenis laporan yang bisa dihasilkan, seperti laporan kehadiran, laporan cuti, laporan proyek, dan lainnya.
-   Laporan-laporan ini dapat diekspor dalam format yang dapat disesuaikan (misalnya PDF, Excel) untuk memudahkan analisis lebih lanjut.
-   Direksi dan manajer proyek dapat memantau status proyek dan kehadiran tim melalui laporan yang dihasilkan oleh sistem.

## Teknologi yang Digunakan

-   **Backend**: PHP (MagigAppBuilder dan MagicObject)
-   **Frontend**: HTML, CSS, JavaScript
-   **Database**: MySQL
-   **Web Server**: Apache
-   **Hosting**: Private Server (baremetal)

Aplikasi ini dibangun menggunakan **PHP** dengan **MagigAppBuilder** dan **MagicObject** untuk backend, serta **MySQL** sebagai database. Web server yang digunakan adalah **Apache** yang di-host pada **private server baremetal** untuk memberikan performa dan kontrol penuh terhadap server dan jaringan.

## Dependensi

1. MagicApp
2. MagicObject
3. Symfony Yaml
4. PHPMailer

### Memasukkan Dependensi

```bash
composer require planetbiru/magic-app
composer require phpmailer/phpmailer
composer update --ignore-platform-reqs
```

atau

```bash
php composer.phar require planetbiru/magic-app
php composer.phar require phpmailer/phpmailer
php composer.phar update --ignore-platform-reqs
```

Kedua perintah di atas dimaksudkan untuk memasukkan repositori dependensi.

`composer update --ignore-platform-reqs` digunakan untuk memastikan bahwa aplikasi dapat berjalan di bawah PHP 5. Jika menggunakan PHP 7.2 ke atas, perintah ini tidak perlu diberikan.

Dalam hal ini, saat memasukkan **MagicApp**, `composer` secara otomatis juga akan memasukkan **MagicObject** dan **Symfony Yaml** sesuai dependensi dari **MagicApp** dan **MagicObject**.

### Perbarui Dependensi

```bash
composer update --ignore-platform-reqs
```

atau

```bash
php composer.phar update --ignore-platform-reqs
```

Kedua perintah di atas dimaksudkan untuk mengupdate dependensi.