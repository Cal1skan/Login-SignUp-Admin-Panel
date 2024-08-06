<?php
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
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if ($password === $confirm_password) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
      $message = "New record created successfully";
    } else {
      $message = "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    $message = "Passwords do not match!";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cal1skan - Kayıt Ol</title>
  <link rel="stylesheet" href="../Css/login.css">
</head>
<body>
  <div class="wrapper">
    <?php if ($message != "") { echo "<p>$message</p>"; } ?>

    <form action="" method="POST">
      <h2>Kayıt Ol</h2>
      <div class="input-field">
        <input type="email" name="email" required>
        <label>E-Posta Adresiniz</label>
      </div>
      <div class="input-field">
        <input type="text" name="username" required>
        <label>Kullanıcı Adı</label>
      </div>
      <div class="input-field">
        <input type="password" name="password" required>
        <label>Şifrenizi Giriniz</label>
      </div>
      <div class="input-field">
        <input type="password" name="confirm_password" required>
        <label>Tekrar Şifre Giriniz</label>
      </div>
      <button type="submit">Kayıt Ol</button>
      <div class="register">
        <p>Zaten Hesabınız Var Mı? <a href="login.php">Giriş Yap</a></p>
      </div>
    </form>
  </div>
</body>
</html>
