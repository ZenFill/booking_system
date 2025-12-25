<?php
// admin/bookings.php
session_start();
require_once '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// QUERY JOIN 3 TABEL
$query = "SELECT b.*, u.name as user_name, r.room_name 
          FROM bookings b 
          JOIN users u ON b.user_id = u.id 
          JOIN rooms r ON b.room_id = r.id 
          ORDER BY b.created_at DESC";

$result = mysqli_query($conn, $query);

require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Pengajuan Peminjaman</h2>
    <p class="text-gray-600">Kelola persetujuan peminjaman ruangan yang masuk.</p>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Peminjam</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ruangan</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu & Keperluan</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 font-bold"><?= $row['user_name'] ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900"><?= $row['room_name'] ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="text-gray-900">
                            <span class="font-bold text-xs text-gray-500">Mulai:</span> <?= date('d M H:i', strtotime($row['start_time'])) ?><br>
                            <span class="font-bold text-xs text-gray-500">Selesai:</span> <?= date('d M H:i', strtotime($row['end_time'])) ?>
                        </div>
                        <p class="text-gray-500 italic mt-1 text-xs">"<?= substr($row['purpose'], 0, 30) ?>..."</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <?php
                        $statusClass = 'bg-yellow-100 text-yellow-800';
                        if ($row['status'] == 'approved') $statusClass = 'bg-green-100 text-green-800';
                        if ($row['status'] == 'rejected') $statusClass = 'bg-red-100 text-red-800';
                        ?>
                        <span class="px-3 py-1 font-semibold <?= $statusClass ?> rounded-full text-xs">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <?php if ($row['status'] == 'pending'): ?>
                            <div class="flex space-x-2">
                                <a href="booking_action.php?id=<?= $row['id'] ?>&action=approve"
                                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition btn-approve"
                                    title="Setujui">
                                    ✓
                                </a>
                                <a href="booking_action.php?id=<?= $row['id'] ?>&action=reject"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition btn-reject"
                                    title="Tolak">
                                    ✕
                                </a>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-xs">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 1. LOGIKA TOMBOL APPROVE (HIJAU)
        const approveBtns = document.querySelectorAll('.btn-approve');
        approveBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                Swal.fire({
                    title: 'Setujui Peminjaman?',
                    text: "Status akan berubah menjadi Approved.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981', // Warna Hijau Tailwind
                    confirmButtonText: 'Ya, Setujui!'
                }).then((result) => {
                    if (result.isConfirmed) document.location.href = href;
                });
            });
        });

        // 2. LOGIKA TOMBOL REJECT (MERAH)
        const rejectBtns = document.querySelectorAll('.btn-reject');
        rejectBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                Swal.fire({
                    title: 'Tolak Peminjaman?',
                    text: "Peminjaman akan dibatalkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444', // Warna Merah Tailwind
                    confirmButtonText: 'Ya, Tolak!'
                }).then((result) => {
                    if (result.isConfirmed) document.location.href = href;
                });
            });
        });

    });
</script>

<?php require_once '../layouts/footer.php'; ?>