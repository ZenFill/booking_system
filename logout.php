<?php
// logout.php
session_start();
session_unset();
session_destroy(); // Hapus semua data sesi

header("Location: login.php");
exit;
