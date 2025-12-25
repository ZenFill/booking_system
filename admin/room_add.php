<?php
// admin/room_add.php
session_start();
require_once '../config/db.php';

// 1. Cek Security: Hanya Admin yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// 2. Logika Pemrosesan Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['room_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $capacity = (int) $_POST['capacity'];

    // --- PROSES UPLOAD FOTO ---
    $target_dir = "../uploads/";

    // Ambil ekstensi file (jpg, png, dll)
    $imageFileType = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));

    // Buat nama file unik (gabungan waktu + angka acak) agar tidak tertimpa
    $new_file_name = time() . '_' . uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_file_name;

    $uploadOk = 1;

    // Validasi 1: Cek apakah file benar-benar gambar
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['error'] = "File yang dipilih bukan gambar.";
        $uploadOk = 0;
    }

    // Validasi 2: Cek format file (Opsional, untuk keamanan)
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $_SESSION['error'] = "Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $uploadOk = 0;
    }

    // Jika Lolos Validasi
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Insert ke Database
            $sql = "INSERT INTO rooms (room_name, description, capacity, photo) 
                    VALUES ('$name', '$desc', $capacity, '$new_file_name')";

            if (mysqli_query($conn, $sql)) {
                // SUKSES: Set Session Message & Redirect ke Tabel
                $_SESSION['success'] = "Ruangan berhasil ditambahkan!";
                header("Location: rooms.php");
                exit; // Penting: Hentikan script setelah redirect
            } else {
                $_SESSION['error'] = "Database Error: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['error'] = "Maaf, terjadi error saat mengupload gambar ke folder.";
        }
    }
}

// 3. Panggil Template Layout
require_once '../layouts/header.php';
require_once '../layouts/navbar.php';
?>

<div class="container mx-auto mt-10 p-4 max-w-2xl">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <div class="border-b pb-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Ruangan Baru</h2>
            <p class="text-gray-500 text-sm">Isi detail ruangan dengan lengkap.</p>
        </div>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">

            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Ruangan</label>
                <input type="text" name="room_name" required placeholder="Contoh: Aula Serbaguna"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Deskripsi & Fasilitas</label>
                <textarea name="description" required rows="4" placeholder="Contoh: AC, Proyektor, Sound System..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Kapasitas (Orang)</label>
                <input type="number" name="capacity" required placeholder="Contoh: 50"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Foto Ruangan</label>
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col w-full h-32 border-2 border-dashed hover:bg-gray-100 hover:border-blue-300 group">
                        <div class="flex flex-col items-center justify-center pt-7">
                            <svg class="w-10 h-10 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-blue-500">Pilih Foto (JPG/PNG)</p>
                        </div>
                        <input type="file" name="photo" class="opacity-0" required />
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="rooms.php" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-1">
                    Simpan Ruangan
                </button>
            </div>
        </form>
    </div>
</div>

<?php
// Panggil Footer (Script SweetAlert ada di sini)
require_once '../layouts/footer.php';
?>