<?php
// Pastikan session sudah start, kalau belum kita start (untuk jaga-jaga)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek Role untuk menentukan warna dan menu
$role = $_SESSION['role'] ?? 'guest'; // Kalau gak ada session, anggap guest
$navColor = ($role == 'admin') ? 'bg-blue-900' : 'bg-indigo-600';
?>

<nav class="<?= $navColor ?> text-white shadow-lg">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="#" class="flex items-center gap-2">
            <img src="../assets/img/logo.png" alt="Logo" class="h-8 w-auto">

            <span class="text-xl font-bold tracking-wide">
                <?php if ($role == 'admin'): ?>
                    Admin Panel
                <?php else: ?>
                    Booking App
                <?php endif; ?>
            </span>
        </a>

        <div class="space-x-4 text-sm font-medium">
            <?php if ($role == 'admin'): ?>
                <a href="../admin/dashboard.php" class="hover:text-gray-300">Dashboard</a>
                <a href="../admin/rooms.php" class="hover:text-gray-300">Ruangan</a>
                <a href="../admin/bookings.php" class="hover:text-gray-300">Booking Masuk</a>
            <?php elseif ($role == 'user'): ?>
                <a href="../user/dashboard.php" class="hover:text-gray-300">Dashboard</a>
                <a href="../user/my_bookings.php" class="hover:text-gray-300">Riwayat Saya</a>
            <?php endif; ?>

            <a href="../logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-2 rounded transition">Logout</a>
        </div>
    </div>
</nav>

<main class="container mx-auto p-6 flex-grow">