<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "caliskan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loggedIn = isset($_SESSION['user']) || isset($_COOKIE['user']);
$userEmail = $_SESSION['user'] ?? $_COOKIE['user'] ?? '';

if (!$loggedIn) {
    header("Location: login.php");
    exit();
}

$message = "";
$currentUser = null;

$sql = "SELECT * FROM users WHERE email='$userEmail'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $currentUser = $result->fetch_assoc();
    $username = $currentUser['username'];
    $profilePicture = $currentUser['profile_picture'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newEmail = $_POST['email'];
    $username = $_POST['username'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile);
        $profilePicture = $targetFile;
    }

    if (!empty($newPassword)) {
        if (!password_verify($currentPassword, $currentUser['password'])) {
            $message = "Mevcut şifre yanlış.";
        } elseif ($newPassword !== $confirmPassword) {
            $message = "Yeni şifreler eşleşmiyor.";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET email='$newEmail', username='$username', password='$hashedPassword', profile_picture='$profilePicture' WHERE email='$userEmail'";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['user'] = $newEmail;
                $_SESSION['username'] = $username;
                $_SESSION['profile_picture'] = $profilePicture;
                setcookie('user', $newEmail, time() + (86400 * 30), "/");
                setcookie('username', $username, time() + (86400 * 30), "/");
                setcookie('profile_picture', $profilePicture, time() + (86400 * 30), "/");
                $message = "Profil güncellendi.";
            } else {
                $message = "Hata: " . $conn->error;
            }
        }
    } else {
        $sql = "UPDATE users SET email='$newEmail', username='$username', profile_picture='$profilePicture' WHERE email='$userEmail'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['user'] = $newEmail;
            $_SESSION['username'] = $username;
            $_SESSION['profile_picture'] = $profilePicture;
            setcookie('user', $newEmail, time() + (86400 * 30), "/");
            setcookie('username', $username, time() + (86400 * 30), "/");
            setcookie('profile_picture', $profilePicture, time() + (86400 * 30), "/");
            $message = "Profil güncellendi.";
        } else {
            $message = "Hata: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil</title>
  <link rel="stylesheet" href="../Css/profile.css">
</head>
<body>


<?php include 'login-header.php'; ?>
  <div class="wrapper">
    <h1>Hoşgeldin, <?php echo htmlspecialchars($username); ?>!</h1>
    <p class="message"><?php echo $message; ?></p>
    <form action="" method="post" enctype="multipart/form-data">
      <label for="email">E-posta:</label>
      <input type="email" name="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" required>
      <label for="username">Kullanıcı Adı:</label>
      <input type="text" name="username" value="<?php echo htmlspecialchars($currentUser['username']); ?>" required>
      <label for="current_password">Mevcut Şifre:</label>
      <input type="password" name="current_password">
      <label for="new_password">Yeni Şifre:</label>
      <input type="password" name="new_password">
      <label for="confirm_password">Yeni Şifre (Tekrar):</label>
      <input type="password" name="confirm_password">
      <label for="profile_picture">Profil Fotoğrafı:</label>
      <input type="file" name="profile_picture">
      <button type="submit">Güncelle</button>
    </form>
    <a href="index.php">Anasayfaya Dön</a>
  </div>
</body>
</html>
