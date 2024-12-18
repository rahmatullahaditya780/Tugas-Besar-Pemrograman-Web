<?php
session_start();

if (isset($_SESSION['username'])) {
    // Update waktu terakhir aktivitas pengguna
    $_SESSION['last_login_time'] = time();
    echo "Session updated";
} else {
    echo "Session expired";
}
?>
