<?php
// user/dashboard.php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

$query = "SELECT * FROM rooms";
$result = mysqli_query($conn, $query);

// --- TEMPLATE BARU ---
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
?>

<div class="mb-8 text-center">
    <h2 class="text-3xl font-bold text-gray-800">Katalog Ruangan</h2>
    <p class="text-gray-600 mt-2">Pilih ruangan yang sesuai dengan kebutuhan meeting Anda.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:-translate-y-2 transition-transform duration-300">
            <div class="relative">
                <img src="../uploads/<?= $row['photo'] ?>" alt="<?= $row['room_name'] ?>" class="w-full h-48 object-cover">
                <div class="absolute top-0 right-0 bg-indigo-600 text-white text-xs px-2 py-1 m-2 rounded">
                    Kapasitas: <?= $row['capacity'] ?>
                </div>
            </div>

            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2"><?= $row['room_name'] ?></h3>
                <p class="text-gray-500 text-sm mb-4 h-12 overflow-hidden"><?= substr($row['description'], 0, 90) ?>...</p>

                <a href="book.php?room_id=<?= $row['id'] ?>"
                    class="block text-center w-full bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 transition">
                    Booking Sekarang
                </a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php
require_once '../layouts/footer.php';
?>