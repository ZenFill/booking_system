<?php
// user/search_logic.php
require_once '../config/db.php';

// Ambil kata kunci dari URL
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$search_param = "%" . $keyword . "%";

// Query Cari ruangan menggunakan Prepared Statement
$query = "SELECT * FROM rooms WHERE room_name LIKE ? OR description LIKE ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Jika ada hasil, tampilkan kartunya
    if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
            ?>
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
            <?php
        endwhile;
    else:
        // Jika tidak ada hasil
        ?>
        <div class="col-span-1 md:col-span-3 text-center py-10">
            <p class="text-gray-500 text-lg">Tidak ada ruangan yang cocok dengan kata kunci
                "<b><?= htmlspecialchars($keyword) ?></b>".</p>
        </div>
    <?php endif;
    mysqli_stmt_close($stmt);
} else {
    echo "Error Search System";
}
?>