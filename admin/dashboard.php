<?php
// admin/dashboard.php
session_start();
require_once '../config/db.php';

// Cek Security
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Logic Data (Tetap dipertahankan)
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms"))['total'];
$pending_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status='pending'"))['total'];
$approved_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status='approved'"))['total'];

// --- BAGIAN TAMPILAN (VIEW) DIMULAI ---
require_once '../layouts/header.php'; // Panggil Kepala
require_once '../layouts/navbar.php'; // Panggil Menu
?>

<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Dashboard Ringkasan</h2>
    <p class="text-gray-600">Selamat datang kembali, <strong><?= $_SESSION['name']; ?></strong></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-sm uppercase font-bold">Total Ruangan</h3>
        <p class="text-4xl font-bold text-gray-800 mt-2"><?= $total_rooms; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
        <h3 class="text-gray-500 text-sm uppercase font-bold">Booking Pending</h3>
        <p class="text-4xl font-bold text-gray-800 mt-2"><?= $pending_bookings; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
        <h3 class="text-gray-500 text-sm uppercase font-bold">Booking Disetujui</h3>
        <p class="text-4xl font-bold text-gray-800 mt-2"><?= $approved_bookings; ?></p>
    </div>
</div>

<?php
require_once '../layouts/footer.php'; // Panggil Kaki
?>