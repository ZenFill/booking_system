<?php
// admin/rooms.php
session_start();
require_once '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua data ruangan
$result = mysqli_query($conn, "SELECT * FROM rooms ORDER BY id DESC");

require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Ruangan</h2>
    <a href="room_add.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 shadow flex items-center gap-2">
        <span>+</span> Tambah Ruangan
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Ruangan</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kapasitas</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <img src="../uploads/<?= $row['photo'] ?>" class="w-16 h-16 object-cover rounded shadow-sm">
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 font-bold"><?= $row['room_name'] ?></p>
                        <p class="text-gray-500 text-xs mt-1"><?= substr($row['description'], 0, 50) ?>...</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            <?= $row['capacity'] ?> Orang
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="room_edit.php?id=<?= $row['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3 font-medium">Edit</a>

                        <a href="room_delete.php?id=<?= $row['id'] ?>"
                            class="text-red-600 hover:text-red-900 font-medium btn-delete">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    // Tunggu sampai halaman selesai loading
    document.addEventListener('DOMContentLoaded', function() {

        // Cari semua tombol yang punya class 'btn-delete'
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // 1. Matikan fungsi link asli (jangan pindah halaman dulu)

                const href = this.getAttribute('href'); // 2. Ambil alamat linknya

                // 3. Tampilkan SweetAlert Konfirmasi
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data ruangan beserta booking terkait akan hilang permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 4. Jika user klik "Ya", baru kita arahkan ke link delete
                        document.location.href = href;
                    }
                });
            });
        });
    });
</script>

<?php
require_once '../layouts/footer.php';
?>