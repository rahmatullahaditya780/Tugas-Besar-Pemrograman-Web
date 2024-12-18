<?php
// Konfigurasi halaman
$title = "ARnime";
$anime_list = [
    ["img" => "assets/img/popular-3.jpg", "title" => "Anime Title 1"],
    ["img" => "assets/img/popular-4.jpg", "title" => "Anime Title 2"],
    ["img" => "assets/img/popular-5.jpg", "title" => "Anime Title 3"],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>" />
</head>
<body>
    <!-- Header Section Begin -->
    <header>
        <nav class="navbar">
            <div class="logo">AR<span>nime</span></div>
            <ul class="nav-links">
                <li class="active"><a href="index.php">Home</a></li>
                <li><a href="/Tubes/login.php">Login</a></li>
            </ul>
        </nav>
    </header>
    <!-- Header End -->

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Anime World</h1>
            <p>Your one-stop place for the best anime collection.</p>
            <a href="/Tubes/login.php" class="btn">Explore Now</a>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Main Content Begin -->
    <main>
        <section class="anime-list">
            <h2>Popular Anime</h2>
            <div class="anime-grid">
                <?php foreach ($anime_list as $anime): ?>
                    <div class="anime-item">
                        <img src="<?= $anime['img']; ?>" alt="<?= $anime['title']; ?>">
                        <h4><?= $anime['title']; ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <!-- Main Content End -->

    <!-- Footer Section Begin -->
    <footer>
        <p>&copy; <?= date("Y"); ?> ARnime. All Rights Reserved.</p>
    </footer>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script>
        // Simple interaction for future use
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', () => {
                alert('Silahkan login terlebih dahulu!');
            });
        });
    </script>
</body>
</html>
