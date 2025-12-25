</main>
<footer class="bg-gray-800 text-white py-6 mt-auto">
    <div class="container mx-auto text-center text-sm">
        <p>&copy; <?= date('Y'); ?> Sistem Booking Profesional.</p>
    </div>
</footer>

<script>
    <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $_SESSION['success']; ?>',
            showConfirmButton: false,
            timer: 2000 // Hilang otomatis dalam 2 detik
        });
        <?php unset($_SESSION['success']); // Hapus pesan agar tidak muncul terus 
        ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= $_SESSION['error']; ?>',
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</script>

</body>

</html>