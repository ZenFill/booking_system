<?php
// user/ticket.php
session_start();
require_once '../config/db.php';

// 1. Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// 2. Ambil ID Booking & Validasi Kepemilikan
if (!isset($_GET['id'])) {
    die("ID Booking tidak ditemukan.");
}

$booking_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

// Query Detail Booking + Nama Ruangan + Nama User
$query = "SELECT b.*, r.room_name, r.description, u.name as user_name, u.email 
          FROM bookings b
          JOIN rooms r ON b.room_id = r.id
          JOIN users u ON b.user_id = u.id
          WHERE b.id = $booking_id AND b.user_id = $user_id";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// 3. Validasi Data
if (!$data) {
    die("Data tidak ditemukan atau Anda tidak memiliki akses.");
}

if ($data['status'] !== 'approved') {
    die("Booking belum disetujui, tidak bisa cetak tiket.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>E-Ticket Booking #<?= $data['id'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');

        body {
            font-family: 'Courier Prime', monospace;
            /* Font ala tiket */
        }

        /* CSS KHUSUS UNTUK CETAK (PRINT) */
        @media print {
            .no-print {
                display: none !important;
                /* Sembunyikan tombol saat dicetak */
            }

            body {
                background-color: white;
            }

            .ticket-container {
                box-shadow: none;
                border: 2px solid black;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div
        class="ticket-container bg-white max-w-lg w-full p-8 rounded-lg shadow-2xl border-t-8 border-indigo-600 relative">

        <div class="text-center border-b-2 border-dashed border-gray-300 pb-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 uppercase tracking-widest">E-TICKET</h1>
            <p class="text-sm text-gray-500 mt-1">Sistem Booking Ruangan Profesional</p>
        </div>

        <div class="space-y-4 mb-6">
            <div class="flex justify-between">
                <span class="text-gray-500">ID Booking</span>
                <span class="font-bold text-xl">#<?= str_pad($data['id'], 6, '0', STR_PAD_LEFT) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Peminjam</span>
                <span class="font-bold"><?= strtoupper($data['user_name']) ?></span>
            </div>
            <div class="border-t border-gray-200 my-2"></div>

            <div class="bg-gray-50 p-4 rounded border">
                <p class="text-xs text-gray-500 uppercase">Ruangan</p>
                <p class="text-2xl font-bold text-indigo-700"><?= $data['room_name'] ?></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Mulai</p>
                    <p class="font-bold"><?= date('d M Y', strtotime($data['start_time'])) ?></p>
                    <p class="text-lg"><?= date('H:i', strtotime($data['start_time'])) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase">Selesai</p>
                    <p class="font-bold"><?= date('d M Y', strtotime($data['end_time'])) ?></p>
                    <p class="text-lg"><?= date('H:i', strtotime($data['end_time'])) ?></p>
                </div>
            </div>

            <div class="border-t border-gray-200 my-2"></div>
            <div>
                <span class="text-gray-500 text-sm">Keperluan:</span>
                <p class="italic">"<?= $data['purpose'] ?>"</p>
            </div>
        </div>

        <div class="text-center mt-8">
            <div class="bg-black h-12 w-3/4 mx-auto mb-2"></div>
            <p class="text-xs text-gray-400">Tunjukkan tiket ini kepada petugas.</p>
            <p class="text-xs text-gray-400 mt-1">Dicetak pada: <?= date('d M Y H:i') ?></p>
        </div>

        <div class="no-print mt-8 flex gap-4 justify-center">
            <button onclick="window.print()"
                class="bg-indigo-600 text-white px-6 py-2 rounded shadow hover:bg-indigo-700 font-sans font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak Tiket
            </button>
            <button onclick="window.close()"
                class="bg-gray-300 text-gray-700 px-6 py-2 rounded shadow hover:bg-gray-400 font-sans">
                Tutup
            </button>
        </div>

    </div>
</body>

</html>