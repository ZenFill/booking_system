<?php
// admin/room_edit.php
session_start();
require_once '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int) $_GET['id'];
$error = '';
$success = '';

// Ambil data ruangan yang mau diedit
$query = "SELECT * FROM rooms WHERE id = $id";
$result = mysqli_query($conn, $query);
$room = mysqli_fetch_assoc($result);

if (!$room) {
    die("Data tidak ditemukan!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['room_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $capacity = (int) $_POST['capacity'];
    $photo_name = $room['photo']; // Default: pakai foto lama

    // Cek apakah user upload foto baru?
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../uploads/";
        $new_file_name = time() . '_' . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $new_file_name;

        // Upload file baru
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Hapus foto lama biar server gak penuh
            if (file_exists($target_dir . $room['photo'])) {
                unlink($target_dir . $room['photo']);
            }
            $photo_name = $new_file_name; // Update variabel nama foto
        } else {
            $error = "Gagal upload foto baru.";
        }
    }

    if (!$error) {
        $update_sql = "UPDATE rooms SET room_name='$name', description='$desc', capacity=$capacity, photo='$photo_name' WHERE id=$id";

        if (mysqli_query($conn, $update_sql)) {
            $success = "Data berhasil diperbarui!";
            // Refresh data agar form menampilkan data terbaru
            $room['room_name'] = $name;
            $room['description'] = $desc;
            $room['capacity'] = $capacity;
            $room['photo'] = $photo_name;
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-4 max-w-lg">
        <div class="bg-white p-8 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-6">Edit Ruangan</h2>

            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    <?= $success ?> <a href="rooms.php" class="font-bold underline">Kembali</a>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700">Nama Ruangan</label>
                    <input type="text" name="room_name" value="<?= $room['room_name'] ?>" required class="w-full border p-2 rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Deskripsi</label>
                    <textarea name="description" required class="w-full border p-2 rounded"><?= $room['description'] ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Kapasitas</label>
                    <input type="number" name="capacity" value="<?= $room['capacity'] ?>" required class="w-full border p-2 rounded">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700">Foto Saat Ini</label>
                    <img src="../uploads/<?= $room['photo'] ?>" class="w-32 h-32 object-cover rounded mb-2 border">
                    <label class="block text-sm text-gray-500">Ganti Foto (Biarkan kosong jika tidak ingin mengganti)</label>
                    <input type="file" name="photo" class="w-full border p-2 rounded">
                </div>

                <div class="flex justify-between">
                    <a href="rooms.php" class="text-gray-500 mt-2">Batal</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>