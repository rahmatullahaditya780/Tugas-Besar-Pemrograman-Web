<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ARnime</title>
  <link
    href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
  <!-- Header Section Begin -->
  <header>
    <nav class="navbar">
      <div class="logo">AR<span>nime</span></div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li class="active"><a href="login.html">Login</a></li>
      </ul>
    </nav>
  </header>
  <!-- Header End -->

  <!-- Login Section Begin -->
  <section class="login-spad">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 left-section">
          <div class="login__form">
            <h2>Login</h2>
            <form method="post" action="ceklogin.php">
              <div class="form-group input__item">
                <input type="text" placeholder="Username" name="username" id="username" required>
                <span class="icon_mail"></span>
              </div>
              <div class="form-group input__item">
                <input type="password" placeholder="Password" name="password" id="password" required>
                <span class="icon_lock"></span>
              </div>
              <div>
                <input type="checkbox" name="remember_me" id="remember_me">
                <label for="remember_me">Remember Me</label>
              </div>
              <button type="submit" class="btn-login" value="Login">LOGIN NOW</button>
            </form>
          </div>
        </div>
      </div>
      <div class="extra-section">
        <div class="divider">OR</div>
        <div class="social-login">
          <button class="facebook">
            <i class="ri-facebook-box-fill"></i> SIGN IN WITH FACEBOOK
          </button>
          <button class="google">
            <i class="ri-google-fill"></i> SIGN IN WITH GOOGLE
          </button>
          <button class="twitter">
            <i class="ri-twitter-fill"></i> SIGN IN WITH TWITTER
          </button>
        </div>
      </div>
    </div>
  </section>
  <!-- Login Section End -->

  <!-- Footer Section Begin -->
  <footer>
    <p>&copy; 2024 ARnime. All Rights Reserved.</p>
  </footer>
  <!-- Footer Section End -->

  <!-- Js Plugins -->
  <script>
    // Simple interaction for future use
    document.querySelectorAll(".btn").forEach((button) => {
      button.addEventListener("click", () => {
        alert("Button Clicked!");
      });
    });
  </script>
  <script
    src="https://kit.fontawesome.com/a076d05399.js"
    crossorigin="anonymous"></script>
</body>

</html>