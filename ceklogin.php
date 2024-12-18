<?php
session_start();

// Simulasi daftar username dan password (hashed password)
$users = [
    "Admin" => password_hash("admin123#", PASSWORD_DEFAULT),
    "Anita" => password_hash("pass@anitA2", PASSWORD_DEFAULT),
    "Sapta" => password_hash("pass@saptA3", PASSWORD_DEFAULT),
    "Aditya" => password_hash("adit123#", PASSWORD_DEFAULT)
];

// Fungsi: Redirect dengan pesan alert
function redirectWithAlert($message, $url = "login.php")
{
    echo "<script>alert('$message');</script>";
    echo "<meta http-equiv='refresh' content='1;url=$url'>";
    exit();
}

// Ambil dan sanitasi input
$username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$rememberMe = isset($_POST['remember_me']);

// Validasi input kosong
if (empty($username) || empty($password)) {
    redirectWithAlert("Username dan Password belum diisi.");
}

// Cek username dalam daftar
if (isset($users[$username])) {
    // Verifikasi password
    if (password_verify($password, $users[$username])) {
        // Set session
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['last_login_time'] = time();

        // Set cookie jika Remember Me dicentang
        if ($rememberMe) {
            setcookie('username', $username, time() + 60, "/"); // Berlaku 1 menit
        } else {
            setcookie('username', '', time() - 3600, "/"); // Hapus cookie
        }

        // Redirect berdasarkan username
        if ($username === "Admin") {
            // Jika username Admin, arahkan ke dashboard-admin.php
            echo "<script>
                    alert('Login Berhasil. Selamat datang, Admin!');
                    window.location.href = 'dashboard-admin.php';
                  </script>";
        } else {
            // Jika bukan Admin, arahkan ke dashboard-user.php
            echo "<script>
                    alert('Login Berhasil. Selamat datang, $username!');
                    window.location.href = 'dashboard-user.php';
                  </script>";
        }
        exit();
    } else {
        redirectWithAlert("Password yang dimasukkan salah.");
    }
} else {
    redirectWithAlert("Username tidak terdaftar.");
}
?>
