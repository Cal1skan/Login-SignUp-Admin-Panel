<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
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

$message = "";

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id='$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $message = "Kullanıcı bulunamadı.";
    }
} else {
    header("Location: admin_manage_users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    if (!empty($newPassword)) {
        $sql = "UPDATE users SET username='$username', email='$email', password='$hashedPassword' WHERE id='$userId'";
    } else {
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id='$userId'";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "Kullanıcı bilgileri güncellendi.";
    } else {
        $message = "Hata: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cal1skan Admin Paneli - Ayarlar</title>
    <link rel="stylesheet" href="../Css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
            <img src="../assets/White.png" alt="Logo" width="50" height="50">
            </div>
            <ul class="menu">
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Anasayfa</a></li>
                <li><a href="admin_manage_users.php"><i class="fas fa-user"></i> Kullanıcılar</a></li>
                <li><a href="admin_settings.php"><i class="fas fa-cog"></i> Ayarlar</a></li>
                <li><a href="admin_categories.php"><i class="fas fa-list"></i> Kategoriler</a></li>
                <li><a href="admin_logs.php"><i class="fas fa-file-alt"></i> Loglar</a></li>
                <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Kullanıcı Düzenle</h1>
            </div>
            <div class="content">
                <p class="message"><?php echo $message; ?></p>
                <form action="" method="post">
                    <label for="username">Kullanıcı Adı:</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    <label for="email">E-Posta:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <label for="new_password">Yeni Şifre:</label>
                    <input type="password" name="new_password">
                    <button type="submit">Güncelle</button>
                </form>
                <a href="admin_manage_users.php">Geri Dön</a>
            </div>
        </div>
    </div>
</body>
</html>
