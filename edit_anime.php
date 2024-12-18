<?php
include 'db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM tb_anime WHERE id_anime = $id");
$row = $result->fetch_assoc();

if (isset($_POST['update_anime'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $image = $_POST['image'];
    $status = $_POST['status'];
    $genre = $_POST['genre'];
    $conn->query("UPDATE tb_anime SET judul='$judul', deskripsi='$deskripsi', image='$image', status='$status', genre='$genre' WHERE id_anime=$id");
    header("Location: dashboard-admin.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anime</title>
</head>
<body>
    <h1>Edit Anime</h1>
    <form method="POST">
        <input type="text" name="judul" value="<?php echo $row['judul']; ?>" required>
        <input type="text" name="deskripsi" value="<?php echo $row['deskripsi']; ?>" required>
        <input type="text" name="image" value="<?php echo $row['image']; ?>" required>
        <input type="text" name="status" value="<?php echo $row['status']; ?>" required>
        <input type="text" name="genre" value="<?php echo $row['genre']; ?>" required>
        <button type="submit" name="update_anime">Update</button>
    </form>
</body>
</html>
