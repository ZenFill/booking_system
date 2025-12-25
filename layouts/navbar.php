<?php
// Pastikan session sudah start, kalau belum kita start (untuk jaga-jaga)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek Role untuk menentukan warna dan menu
$role = $_SESSION['role'] ?? 'guest'; // Kalau gak ada session, anggap guest
$navColor = ($role == 'admin') ? 'bg-blue-900' : 'bg-indigo-600';

// Logic untuk Active State
$current_page = basename($_SERVER['PHP_SELF']);
$activeInfo   = 'bg-white text-indigo-900 px-3 py-2 rounded-md font-bold shadow-sm';
$inactiveInfo = 'text-gray-200 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md transition';
?>

<nav class="<?= $navColor ?> text-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="#" class="flex items-center gap-2">
            <!-- Icon/Logo bisa ditambahkan di sini -->
            <span class="text-xl font-bold tracking-wide">
                <?php if ($role == 'admin'): ?>
                    Admin Panel
                <?php else: ?>
                    Booking App
                <?php endif; ?>
            </span>
        </a>

        <div class="space-x-2 text-sm font-medium flex items-center">
            <?php if ($role == 'admin'): ?>
                <a href="../admin/dashboard.php" class="<?= $current_page == 'dashboard.php' ? $activeInfo : $inactiveInfo ?>">Dashboard</a>
                <a href="../admin/rooms.php" class="<?= $current_page == 'rooms.php' ? $activeInfo : $inactiveInfo ?>">Ruangan</a>
                <a href="../admin/bookings.php" class="<?= $current_page == 'bookings.php' ? $activeInfo : $inactiveInfo ?>">Booking Masuk</a>
            <?php elseif ($role == 'user'): ?>
                <a href="../user/dashboard.php" class="<?= $current_page == 'dashboard.php' ? $activeInfo : $inactiveInfo ?>">Dashboard</a>
                <a href="../user/my_bookings.php" class="<?= $current_page == 'my_bookings.php' ? $activeInfo : $inactiveInfo ?>">Riwayat Saya</a>
            <?php endif; ?>

            <a href="../logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-2 rounded transition">Logout</a>
        </div>
    </div>
</nav>

<main class="container mx-auto p-6 flex-grow">