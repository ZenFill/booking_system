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

<div class="mb-8 flex flex-col md:flex-row justify-between items-end gap-4">
    <div class="text-left">
        <h2 class="text-3xl font-bold text-gray-800">Katalog Ruangan</h2>
        <p class="text-gray-600 mt-2">Pilih ruangan yang sesuai kebutuhan.</p>
    </div>

    <div class="w-full md:w-1/3 relative">
        <input type="text" id="searchInput" placeholder="Cari nama ruangan..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm pl-10">
        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>
</div>

<div id="roomsContainer" class="grid grid-cols-1 md:grid-cols-3 gap-8">
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

<script>
    // Ambil elemen HTML
    const searchInput = document.getElementById('searchInput');
    const roomsContainer = document.getElementById('roomsContainer');

    // Tambahkan "Event Listener" (Pendeteksi Ketikan)
    searchInput.addEventListener('keyup', function () {
        const keyword = this.value;

        // Gunakan Fetch API untuk panggil file PHP di belakang layar
        fetch(`search_logic.php?keyword=${keyword}`)
            .then(response => response.text()) // Ubah respon jadi text HTML
            .then(data => {
                // Ganti isi container dengan hasil pencarian
                roomsContainer.innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    });
</script>

<?php
require_once '../layouts/footer.php';
?>