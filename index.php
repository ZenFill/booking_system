<?php
// index.php - Halaman Depan (Landing Page)
session_start();

// Jika sudah login, langsung lempar ke dashboard masing-masing
// Jika sudah login, langsung lempar ke dashboard masing-masing
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Booking Ruangan Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.6);
        }
    </style>
</head>

<body class="bg-gray-50">

    <nav class="bg-white shadow-lg fixed w-full z-10">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a class="font-bold text-2xl text-indigo-600" href="#">BookingApp</a>
            <div>
                <a href="login.php" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                <a href="register.php" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 ml-2">Daftar</a>
            </div>
        </div>
    </nav>

    <div class="hero-bg h-screen flex items-center justify-center relative">
        <div class="overlay absolute inset-0"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-5xl font-bold text-white mb-4">Kelola Peminjaman Ruangan <br> Lebih Mudah & Cepat</h1>
            <p class="text-xl text-gray-200 mb-8">Sistem manajemen ruangan anti-bentrok jadwal. Cocok untuk kantor, kampus, dan co-working space.</p>
            <a href="register.php" class="bg-white text-indigo-600 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition duration-300">
                Mulai Sekarang
            </a>
        </div>
    </div>

    <div class="container mx-auto px-6 py-20">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Kenapa Memilih Kami?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 bg-white rounded-lg shadow-md text-center">
                <div class="text-4xl mb-4">⚡</div>
                <h3 class="text-xl font-bold mb-2">Real-time Booking</h3>
                <p class="text-gray-600">Cek ketersediaan ruangan secara langsung tanpa perlu tanya admin berulang kali.</p>
            </div>
            <div class="p-6 bg-white rounded-lg shadow-md text-center">
                <div class="text-4xl mb-4">🛡️</div>
                <h3 class="text-xl font-bold mb-2">Anti-Conflict Logic</h3>
                <p class="text-gray-600">Sistem cerdas kami otomatis mencegah dua orang membooking ruangan di jam yang sama.</p>
            </div>
            <div class="p-6 bg-white rounded-lg shadow-md text-center">
                <div class="text-4xl mb-4">📱</div>
                <h3 class="text-xl font-bold mb-2">Mobile Friendly</h3>
                <p class="text-gray-600">Akses dashboard dan ajukan peminjaman dari smartphone Anda kapan saja.</p>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 Booking System Professional. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>