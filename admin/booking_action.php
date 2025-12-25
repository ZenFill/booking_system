<?php
// admin/booking_action.php
session_start();
require_once '../config/db.php';

// Cek Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];
    $new_status = '';

    if ($action == 'approve') {
        $new_status = 'approved';
        $msg = "Booking berhasil disetujui!";
    } elseif ($action == 'reject') {
        $new_status = 'rejected';
        $msg = "Booking berhasil ditolak.";
    } else {
        header("Location: bookings.php");
        exit;
    }

    $sql = "UPDATE bookings SET status = '$new_status' WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // SET SESSION FLASH MESSAGE
        $_SESSION['success'] = $msg;
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
}

// Balik ke tabel
header("Location: bookings.php");
exit;
