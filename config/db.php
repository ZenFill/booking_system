<?php
// config/db.php

$host = 'localhost';
$user = 'root';     // Default user XAMPP/Laragon
$pass = '';         // Default password biasanya kosong
$db   = 'booking_db';

// Matikan error reporting default mysqli agar tidak bocor
mysqli_report(MYSQLI_REPORT_OFF);

// Membuat koneksi menggunakan MySQLi
$conn = @mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    // Log error asli ke server log (jangan tampilkan ke user)
    error_log("Connection failed: " . mysqli_connect_error());
    
    // Tampilkan pesan user-friendly
    die("Maaf, terjadi gangguan koneksi ke sistem database. Hubungi Administrator.");
}

// Opsional: Set timezone agar waktu booking sesuai WIB
date_default_timezone_set('Asia/Jakarta');
