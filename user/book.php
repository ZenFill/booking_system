<?php
// user/book.php
session_start();
require_once '../config/db.php';

// 1. Cek User Login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// 2. Cek apakah ada ID Ruangan yang dipilih
if (!isset($_GET['room_id'])) {
    header("Location: dashboard.php");
    exit;
}

$room_id = (int) $_GET['room_id'];
$user_id = $_SESSION['user_id'];

// Ambil info ruangan (Biar user tahu dia booking ruangan apa)
$query_room = mysqli_query($conn, "SELECT * FROM rooms WHERE id = $room_id");
$room = mysqli_fetch_assoc($query_room);

// Jika ruangan tidak ditemukan (misal user asal ketik ID di URL)
if (!$room) {
    header("Location: dashboard.php");
    exit;
}

// 3. LOGIKA PEMROSESAN BOOKING
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitasi format waktu (HTML5 datetime-local pakai 'T', MySQL pakai spasi)
    $start_time = str_replace('T', ' ', $_POST['start_time']);
    $end_time = str_replace('T', ' ', $_POST['end_time']);
    $purpose = $_POST['purpose']; // Tidak butuh real_escape_string karena pakai PREPARED STATEMENT

    // Validasi Waktu: Selesai harus setelah Mulai
    if (strtotime($end_time) <= strtotime($start_time)) {
        $_SESSION['error'] = "Waktu selesai harus lebih akhir dari waktu mulai!";
    } else {
        // --- THE KILLER LOGIC (Cek Bentrok) ---
        // Gunakan Prepared Statement untuk cek bentrok
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM bookings 
                        WHERE room_id = ? 
                        AND status IN ('pending', 'approved')
                        AND (
                            (? < end_time) AND (? > start_time)
                        )");
        
        mysqli_stmt_bind_param($check_stmt, "iss", $room_id, $start_time, $end_time);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // JIKA BENTROK
            $_SESSION['error'] = "Gagal! Ruangan sudah dibooking orang lain di jam tersebut.";
        } else {
            // JIKA AMAN -> SIMPAN (Prepared Statement)
            $insert_stmt = mysqli_prepare($conn, "INSERT INTO bookings (user_id, room_id, start_time, end_time, purpose, status) 
                           VALUES (?, ?, ?, ?, ?, 'pending')");
            
            mysqli_stmt_bind_param($insert_stmt, "iisss", $user_id, $room_id, $start_time, $end_time, $purpose);

            if (mysqli_stmt_execute($insert_stmt)) {
                $_SESSION['success'] = "Booking Berhasil! Menunggu persetujuan Admin.";
                header("Location: my_bookings.php"); // Lempar ke halaman Riwayat
                exit;
            } else {
                error_log("Booking Insert Error: " . mysqli_error($conn));
                $_SESSION['error'] = "Terjadi kesalahan sistem.";
            }
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}

// 4. Load Layout
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
?>

<div class="container mx-auto mt-10 p-4 max-w-4xl">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">

        <div class="md:w-1/2 bg-gray-100 relative">
            <img src="../uploads/<?= $room['photo'] ?>" class="w-full h-64 md:h-full object-cover">
            <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black to-transparent w-full p-6 text-white">
                <h2 class="text-3xl font-bold"><?= $room['room_name'] ?></h2>
                <p class="mt-2 text-sm opacity-90"><?= $room['description'] ?></p>
                <div class="mt-4 inline-block bg-white text-indigo-800 text-xs font-bold px-3 py-1 rounded-full">
                    Kapasitas: <?= $room['capacity'] ?> Orang
                </div>
            </div>
        </div>

        <div class="md:w-1/2 p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Isi Formulir Peminjaman</h3>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Mulai Pinjam</label>
                    <input type="datetime-local" name="start_time" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Selesai Pinjam</label>
                    <input type="datetime-local" name="end_time" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Keperluan</label>
                    <textarea name="purpose" required rows="3" placeholder="Contoh: Meeting Tim Marketing"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <a href="dashboard.php" class="text-gray-500 hover:text-gray-700 font-medium">Batal</a>
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 shadow-md transition transform hover:-translate-y-1">
                        Ajukan Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once '../layouts/footer.php';
?>