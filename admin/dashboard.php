<?php
// admin/dashboard.php
session_start();
require_once '../config/db.php';

// Cek Security
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// 1. Logic Data Ringkasan (Card Atas)
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms"))['total'];
$pending_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status='pending'"))['total'];
$approved_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status='approved'"))['total'];

// 2. [BARU] Logic Data Grafik (Top 5 Ruangan Terlaris)
// Kita hitung berapa kali setiap room_id muncul di tabel bookings
$query_chart = "SELECT r.room_name, COUNT(b.id) as total_booking 
                FROM bookings b
                JOIN rooms r ON b.room_id = r.id
                GROUP BY b.room_id
                ORDER BY total_booking DESC
                LIMIT 5";

$result_chart = mysqli_query($conn, $query_chart);

// Siapkan array kosong untuk menampung data
$label_ruangan = [];
$data_jumlah = [];

while ($row = mysqli_fetch_assoc($result_chart)) {
    $label_ruangan[] = $row['room_name']; // Masukkan nama ruangan
    $data_jumlah[] = $row['total_booking']; // Masukkan jumlah booking
}

require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
?>

<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Dashboard Ringkasan</h2>
    <p class="text-gray-600">Selamat datang kembali, <strong><?= $_SESSION['name']; ?></strong></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500 hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm uppercase font-bold">Total Ruangan</h3>
        <p class="text-4xl font-bold text-gray-800 mt-2"><?= $total_rooms; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500 hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm uppercase font-bold">Booking Pending</h3>
        <p class="text-4xl font-bold text-gray-800 mt-2"><?= $pending_bookings; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500 hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm uppercase font-bold">Booking Disetujui</h3>
        <p class="text-4xl font-bold text-gray-800 mt-2"><?= $approved_bookings; ?></p>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">📊 Statistik Peminjaman Terpopuler</h3>

    <div class="relative h-64 w-full">
        <canvas id="bookingChart"></canvas>
    </div>
</div>

<script>
    // Ambil elemen canvas
    const ctx = document.getElementById('bookingChart').getContext('2d');

    // Ambil data dari PHP (di-convert jadi format JSON biar bisa dibaca JS)
    const labels = <?= json_encode($label_ruangan); ?>;
    const dataValues = <?= json_encode($data_jumlah); ?>;

    // Buat Grafik Baru
    new Chart(ctx, {
        type: 'bar', // Tipe grafik: 'bar', 'line', 'pie', 'doughnut'
        data: {
            labels: labels, // Nama Ruangan
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: dataValues, // Angka Jumlah
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)', // Biru
                    'rgba(255, 99, 132, 0.6)', // Merah
                    'rgba(255, 206, 86, 0.6)', // Kuning
                    'rgba(75, 192, 192, 0.6)', // Hijau
                    'rgba(153, 102, 255, 0.6)' // Ungu
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 // Supaya angkanya bulat (1, 2, 3), bukan desimal (1.5 orang)
                    }
                }
            }
        }
    });
</script>

<?php
require_once '../layouts/footer.php';
?>