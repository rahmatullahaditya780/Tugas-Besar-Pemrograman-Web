<?php

if (isset($_GET['anime'], $_GET['episode'])) {
} else {
    die("ERROR PARAMETER NOT VALID");
}

?>

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime Details</title>
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

    <!-- Anime Details Begin -->
    <main class="container main-container">
        <!-- Video Player -->
        <div class="anime__video__player" style="height: 62.5vh;"><iframe id="video_player" allowfullscreen="" frameborder="0" src="https://www.youtube.com/embed/oJrylZutC4g?autoplay=1&amp;mute=1" width="100%" height="100%" style="padding-bottom: 20px;"></iframe></div>

        <!-- Episodes List -->
        <aside class="anime__details__episodes">
            <h3>List Episodes:</h3>
            <ul style="overflow-y:scroll; height: 300px" id="list_episode">

            </ul>
        </aside>
    </main>
    <!-- Anime Information -->
    <div id="detail_anime">

    </div>

    <!-- Anime Details End -->

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
        var id_anime = <?= $_GET['anime'] ?>;
        var episode = <?= $_GET['episode'] ?>;

        $.get("/Tubes/api/detail.php", {
            "anime": id_anime
        }, function(result) {
            console.log(result);
            if (result.status) {
                $("#detail_anime").html(`<h3>${result.detail.judul}</h3>
        <p><strong>Genre: </strong> ${result.detail.genre}</p>
        <p><strong>Sinopsis: </strong> ${result.detail.deskripsi}</p>`);
                // $("#image").html(`<img src="${result.detail.image}" alt="Gambar anime">`)
                var hasil_list = "";
                result.list_episode.forEach((list) => {
                    hasil_list += `<li onclick="window.location='/Tubes/detail.php?anime=${list.id_anime}&episode=${list.episode}'">Episode ${list.episode}</li>`
                })
                $("#list_episode").html(hasil_list);
            } else {
                $("#detail_anime").html(`<center><h1>ERROR SOURCE NOT FOUND</h1></center>`);
                $("#list_episode").hide();
            }
        })

        $.get("/Tubes/api/view.php", {
            "anime": id_anime,
            "episode": episode
        }, function(result) {
            console.log(result);
            $("#video_player").attr("src", result.result.video);
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