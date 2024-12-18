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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ARnime</title>
  <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>" />
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
  <!-- Header Section Begin -->
  <header>
    <?= file_get_contents("./component/navbar.html") ?>
  </header>
  <!-- Header End -->

  <!-- Main Content Begin -->
  <main>
    <section class="anime-list">
      <h2>New Release</h2>
      <div id="list_anime" class="anime-grid">
        <!-- <div class="anime-item">
                <img src="assets/img/party-kara.png" alt="Anime 1">
                <div class="anime-details">
                    <h3>Selasa</h3>
                    <h3>Party kara Tsuihou</h3>
                    <span>Episode 11</span>
                </div>
            </div> -->
      </div>
    </section>
  </main>
  <!-- Main Content End -->

  <!-- Footer Section Begin -->
  <footer>
    <p>&copy; 2024 ARnime. All Rights Reserved.</p>
  </footer>
  <!-- Footer Section End -->

  <!-- Js Plugins -->
  <script
    src="https://code.jquery.com/jquery-3.7.1.js"
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/script.js"></script>
  <script>
    $.get("/Tubes/api/list.php", function(result) {
      var html_data = "";
      result.forEach((anime) => {
        html_data += `<div onclick = "window.location = '/Tubes/detail.php?anime=${anime.detail_anime.id_anime}&episode=${anime.episode}'"class="anime-item">
                <img src="${anime.detail_anime.image}" alt="gambar">
                <div class="anime-details">
                    <h3>${anime.time}</h3>
                    <h3>${anime.detail_anime.judul}</h3>
                    <span>Episode ${anime.episode}</span>
                </div>
            </div>`;

      });

      $("#list_anime").html(html_data);

    })

    $.get("/Tubes/api/list_genre.php", function(result) {
      var html_data = "";
      result.result.forEach((anime) => {
        html_data += `<li><a href="/Tubes/genre.php?genre=${anime.genre}">${anime.genre}</a></li>`;

      });

      $("#list_genre").html(html_data);

    })
  </script>
</body>

</html>