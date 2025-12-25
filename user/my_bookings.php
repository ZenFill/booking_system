<?php
// user/my_bookings.php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT b.*, r.room_name, r.photo 
          FROM bookings b 
          JOIN rooms r ON b.room_id = r.id 
          WHERE b.user_id = $user_id 
          ORDER BY b.created_at DESC";

$result = mysqli_query($conn, $query);

require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Riwayat Peminjaman Saya</h2>
    <p class="text-gray-600">Pantau status pengajuan ruangan Anda di sini.</p>
</div>

<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Ruangan</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Jadwal</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Status</th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <img class="w-10 h-10 rounded-full object-cover mr-4" src="../uploads/<?= $row['photo'] ?>"
                                    alt="" />
                                <div>
                                    <p class="text-gray-900 font-bold"><?= $row['room_name'] ?></p>
                                    <p class="text-gray-500 text-xs mt-1">Keperluan: <?= substr($row['purpose'], 0, 20) ?>...
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900">
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold">Mulai</span>
                                <?= date('d M Y, H:i', strtotime($row['start_time'])) ?>
                            </p>
                            <p class="text-gray-900 mt-1">
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold">Selesai</span>
                                <?= date('d M Y, H:i', strtotime($row['end_time'])) ?>
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <?php
                            $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                            $icon = '⏳';
                            if ($row['status'] == 'approved') {
                                $statusClass = 'bg-green-100 text-green-800 border-green-200';
                                $icon = '✅';
                            }
                            if ($row['status'] == 'rejected') {
                                $statusClass = 'bg-red-100 text-red-800 border-red-200';
                                $icon = '❌';
                            }
                            ?>
                            <span
                                class="relative inline-block px-3 py-1 font-semibold border <?= $statusClass ?> leading-tight rounded-full">
                                <span class="relative"><?= $icon ?>         <?= ucfirst($row['status']) ?></span>
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <?php if ($row['status'] == 'approved'): ?>
                                <a href="ticket.php?id=<?= $row['id'] ?>" target="_blank"
                                    class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded hover:bg-blue-700 transition"
                                    title="Cetak Bukti">
                                    🖨️ Cetak
                                </a>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="p-10 text-center">
            <p class="text-gray-500 text-lg mb-4">Belum ada riwayat booking.</p>
            <a href="dashboard.php" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">Mulai
                Booking</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../layouts/footer.php'; ?>