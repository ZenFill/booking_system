<?php
// admin/room_delete.php
session_start();
require_once '../config/db.php';

// Cek Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // 1. Ambil data dulu untuk hapus foto fisik
    $query = "SELECT photo FROM rooms WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    // 2. Hapus file foto jika ada
    if ($data && file_exists("../uploads/" . $data['photo'])) {
        unlink("../uploads/" . $data['photo']);
    }

    // 3. Hapus data dari database
    $sql = "DELETE FROM rooms WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // [BARU] Set pesan sukses
        $_SESSION['success'] = "Data ruangan berhasil dihapus permanen.";
    } else {
        // [BARU] Set pesan gagal
        $_SESSION['error'] = "Gagal menghapus: " . mysqli_error($conn);
    }
}

// Redirect kembali ke tabel
header("Location: rooms.php");
exit;
