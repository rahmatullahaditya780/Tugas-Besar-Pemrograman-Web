<?php
session_start();
ob_start();

// Fungsi cek kedaluwarsa sesi
function isSessionExpired()
{
    if (isset($_SESSION['last_login_time'])) {
        return (time() - $_SESSION['last_login_time']) > 60;
    }
    return true;
}

// HANDLE LOGOUT
if (isset($_POST['logout'])) {
    session_unset(); // Menghapus semua data sesi
    session_destroy(); // Menghancurkan sesi
    setcookie('username', '', time() - 3600, "/"); // Menghapus cookie jika ada
    echo "<script>alert('Anda telah berhasil logout.');</script>";
    echo "<meta http-equiv='refresh' content='0; url=login.php'>";
    exit();
}

// Periksa sesi, jika kedaluwarsa, redirect ke login
if (!isset($_SESSION['username']) || isSessionExpired()) {
    session_unset();
    session_destroy();
    setcookie('username', '', time() - 3600, "/");
    echo "<script>alert('Silakan login terlebih dahulu.');</script>";
    echo "<meta http-equiv='refresh' content='0; url=login.php'>";
    exit();
}

// Ambil username dari session
$username = $_SESSION['username'];
?>

<?php
include 'db.php';

// HANDLE CREATE & DELETE FOR ANIME
if (isset($_POST['create_anime'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $image = $_POST['image'];
    $status = $_POST['status'];
    $genre = $_POST['genre'];
    $conn->query("INSERT INTO tb_anime (judul, deskripsi, image, status, genre) VALUES ('$judul', '$deskripsi', '$image', '$status', '$genre')");
}
if (isset($_POST['update_anime'])) {
    $id = $_POST['id_anime'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $image = $_POST['image'];
    $status = $_POST['status'];
    $genre = $_POST['genre'];
    $conn->query("UPDATE tb_anime SET judul='$judul', deskripsi='$deskripsi', image='$image', status='$status', genre='$genre' WHERE id_anime=$id");
}
if (isset($_GET['delete_anime'])) {
    $id = $_GET['delete_anime'];
    $conn->query("DELETE FROM tb_anime WHERE id_anime = $id");
}

// HANDLE CREATE & DELETE FOR EPISODE
if (isset($_POST['create_episode'])) {
    $id_anime = $_POST['id_anime'];
    $publisher = $_POST['publisher'];
    $time = $_POST['time'];
    $video = $_POST['video'];
    $episode = $_POST['episode'];
    $conn->query("INSERT INTO tb_episode (id_anime, publisher, time, video, episode) VALUES ('$id_anime', '$publisher', '$time', '$video', '$episode')");
}
if (isset($_POST['update_episode'])) {
    $id = $_POST['id_episode'];
    $id_anime = $_POST['id_anime'];
    $publisher = $_POST['publisher'];
    $time = $_POST['time'];
    $video = $_POST['video'];
    $episode = $_POST['episode'];
    $conn->query("UPDATE tb_episode SET id_anime='$id_anime', publisher='$publisher', time='$time', video='$video', episode='$episode' WHERE id_episode=$id");
}
if (isset($_GET['delete_episode'])) {
    $id = $_GET['delete_episode'];
    $conn->query("DELETE FROM tb_episode WHERE id_episode = $id");
}

// HANDLE CREATE & DELETE FOR GENRE
if (isset($_POST['create_genre'])) {
    $genre = $_POST['genre'];
    $conn->query("INSERT INTO tb_genre (genre) VALUES ('$genre')");
}
if (isset($_POST['update_genre'])) {
    $old_genre = $_POST['old_genre'];
    $new_genre = $_POST['genre'];
    $conn->query("UPDATE tb_genre SET genre='$new_genre' WHERE genre='$old_genre'");
}
if (isset($_GET['delete_genre'])) {
    $genre = $_GET['delete_genre'];
    $conn->query("DELETE FROM tb_genre WHERE genre = '$genre'");
}

// READ DATA
$anime = $conn->query("SELECT * FROM tb_anime");
$episodes = $conn->query("SELECT * FROM tb_episode");
$genres = $conn->query("SELECT * FROM tb_genre");

// HANDLE EDIT
$edit_anime = $edit_episode = $edit_genre = null;
if (isset($_GET['edit_anime'])) {
    $edit_anime = $conn->query("SELECT * FROM tb_anime WHERE id_anime=" . $_GET['edit_anime'])->fetch_assoc();
}
if (isset($_GET['edit_episode'])) {
    $edit_episode = $conn->query("SELECT * FROM tb_episode WHERE id_episode=" . $_GET['edit_episode'])->fetch_assoc();
}
if (isset($_GET['edit_genre'])) {
    $edit_genre = $_GET['edit_genre'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style_admin.css">
    <script>
        let activityTimeout;

        // Fungsi untuk memperbarui sesi
        function updateSession() {
            fetch('session_handler.php')
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Log respon dari server
                });
        }

        // Fungsi reset timer ketika ada aktivitas
        function resetTimer() {
            clearTimeout(activityTimeout);
            updateSession(); // Perbarui sesi
            activityTimeout = setTimeout(() => {
                alert('Sesi Anda telah berakhir karena tidak ada aktivitas.');
                window.location.href = 'login.php'; // Redirect ke login
            }, 60000); // 1 menit timeout
        }

        // Mendeteksi aktivitas pengguna
        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onclick = resetTimer;
        document.onscroll = resetTimer;
    </script>
</head>

<body>
    <h1>Admin Dashboard</h1>

    <!-- ANIME FORM -->
    <h2>Manage Anime</h2>
    <form method="POST">
        <input type="hidden" name="id_anime" value="<?php echo $edit_anime['id_anime'] ?? ''; ?>">
        <input type="text" name="judul" placeholder="Judul" value="<?php echo $edit_anime['judul'] ?? ''; ?>" required>
        <input type="text" name="deskripsi" placeholder="Deskripsi" value="<?php echo $edit_anime['deskripsi'] ?? ''; ?>" required>
        <input type="text" name="image" placeholder="Image URL" value="<?php echo $edit_anime['image'] ?? ''; ?>" required>
        <input type="text" name="status" placeholder="Status" value="<?php echo $edit_anime['status'] ?? ''; ?>" required>
        <input type="text" name="genre" placeholder="Genre" value="<?php echo $edit_anime['genre'] ?? ''; ?>" required>
        <button type="submit" name="<?php echo $edit_anime ? 'update_anime' : 'create_anime'; ?>">
            <?php echo $edit_anime ? 'Update Anime' : 'Add Anime'; ?>
        </button>
    </form>

    <!-- ANIME TABLE -->
    <table>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Image</th>
            <th>Status</th>
            <th>Genre</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $anime->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id_anime']; ?></td>
                <td><?php echo $row['judul']; ?></td>
                <td><?php echo $row['deskripsi']; ?></td>
                <td><img src="<?php echo $row['image']; ?>" width="50"></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['genre']; ?></td>
                <td>
                    <a href="?edit_anime=<?php echo $row['id_anime']; ?>">Edit</a>
                    <a href="?delete_anime=<?php echo $row['id_anime']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- EPISODE FORM -->
    <h2>Manage Episodes</h2>
    <form method="POST">
        <input type="hidden" name="id_episode" value="<?php echo $edit_episode['id_episode'] ?? ''; ?>">
        <input type="number" name="id_anime" placeholder="Anime ID" value="<?php echo $edit_episode['id_anime'] ?? ''; ?>" required>
        <input type="text" name="publisher" placeholder="Publisher" value="<?php echo $edit_episode['publisher'] ?? ''; ?>" required>
        <input type="text" name="time" placeholder="Time" value="<?php echo $edit_episode['time'] ?? ''; ?>" required>
        <input type="text" name="video" placeholder="Video URL" value="<?php echo $edit_episode['video'] ?? ''; ?>" required>
        <input type="number" name="episode" placeholder="Episode Number" value="<?php echo $edit_episode['episode'] ?? ''; ?>" required>
        <button type="submit" name="<?php echo $edit_episode ? 'update_episode' : 'create_episode'; ?>">
            <?php echo $edit_episode ? 'Update Episode' : 'Add Episode'; ?>
        </button>
    </form>

    <!-- EPISODE TABLE -->
    <table>
        <tr>
            <th>ID</th>
            <th>Anime ID</th>
            <th>Publisher</th>
            <th>Time</th>
            <th>Video</th>
            <th>Episode</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $episodes->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id_episode']; ?></td>
                <td><?php echo $row['id_anime']; ?></td>
                <td><?php echo $row['publisher']; ?></td>
                <td><?php echo $row['time']; ?></td>
                <td><?php echo $row['video']; ?></td>
                <td><?php echo $row['episode']; ?></td>
                <td>
                    <a href="?edit_episode=<?php echo $row['id_episode']; ?>">Edit</a>
                    <a href="?delete_episode=<?php echo $row['id_episode']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- GENRE FORM -->
    <h2>Manage Genres</h2>
    <form method="POST">
        <input type="hidden" name="old_genre" value="<?php echo $edit_genre ?? ''; ?>">
        <input type="text" name="genre" placeholder="Genre" value="<?php echo $edit_genre ?? ''; ?>" required>
        <button type="submit" name="<?php echo $edit_genre ? 'update_genre' : 'create_genre'; ?>">
            <?php echo $edit_genre ? 'Update Genre' : 'Add Genre'; ?>
        </button>
    </form>

    <!--GENRE TABLE -->
    <table>
        <tr>
            <th>Genre</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $genres->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['genre']; ?></td>
                <td>
                    <a href="?edit_genre=<?php echo $row['genre']; ?>">Edit</a>
                    <a href="?delete_genre=<?php echo $row['genre']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- LOGOUT BUTTON -->
    <div style="text-align: right; margin: 20px;">
        <form method="POST">
            <button type="submit" name="logout" style="background-color: #ff4d4d; color: white; border: none; padding: 10px 15px; cursor: pointer;">
                Logout
            </button>
        </form>
    </div>

</body>

</html>