<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "caliskan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $remember = isset($_POST['remember']);

  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      $_SESSION['user'] = $row['email'];
      $_SESSION['username'] = $row['username'];
      if ($remember) {
        setcookie('user', $row['email'], time() + (86400 * 30), "/");
        setcookie('username', $row['username'], time() + (86400 * 30), "/");
      }
      header("Location: index.php");
      exit();
    } else {
      $message = "Invalid password";
    }
  } else {
    $message = "No user found with this email";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cal1skan - Giriş Yap</title>
  <link rel="stylesheet" href="../Css/login.css">
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> <!-- Font Awesome için -->
</head>
<body>
  <div class="wrapper">
    <?php if ($message != "") { echo "<p>$message</p>"; } ?>

    <form action="" method="POST">
      <h2>Giriş Yap</h2>
      <div class="input-field">
        <input type="email" name="email" required>
        <label>E-Posta Adresinizi Giriniz</label>
      </div>
      <div class="input-field">
        <input type="password" name="password" required>
        <label>Şifrenizi Giriniz</label>
      </div>
      <div class="forget">
        <label for="remember">
          <input type="checkbox" id="remember" name="remember">
          <p>Beni Hatırla</p>
        </label>
        <a href="#">Şifremi Unuttum</a>
      </div>
      <button type="submit">Giriş Yap</button>
      <div class="register">
        <p>Hesabınız Yok Mu? <a href="register.php">Kayıt Ol</a></p>
      </div>
    </form>
  </div>
</body>
</html>
