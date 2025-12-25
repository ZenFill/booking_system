<?php
// config/db.php

$host = 'localhost';
$user = 'root';     // Default user XAMPP/Laragon
$pass = '';         // Default password biasanya kosong
$db   = 'booking_db';

// Membuat koneksi menggunakan MySQLi (Style Procedural - cocok untuk pemula)
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    // Jika gagal, matikan proses dan tampilkan error
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

// Opsional: Set timezone agar waktu booking sesuai WIB
date_default_timezone_set('Asia/Jakarta');
