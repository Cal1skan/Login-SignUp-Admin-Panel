<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$adminUsername = $_SESSION['admin_username'];
$adminEmail = $_SESSION['admin'];

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

// Şifre güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE admins SET password='$hashedPassword' WHERE email='$adminEmail'";

        if ($conn->query($sql) === TRUE) {
            $message = "Şifre başarıyla güncellendi.";
        } else {
            $message = "Hata: " . $conn->error;
        }
    } else {
        $message = "Yeni şifreler eşleşmiyor.";
    }
}

// Yeni admin ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
    $newAdminUsername = $_POST['admin_username'];
    $newAdminEmail = $_POST['admin_email'];
    $newAdminPassword = $_POST['admin_password'];
    $hashedPassword = password_hash($newAdminPassword, PASSWORD_BCRYPT);

    $sql = "INSERT INTO admins (username, email, password) VALUES ('$newAdminUsername', '$newAdminEmail', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        $message = "Yeni admin başarıyla eklendi.";
    } else {
        $message = "Hata: " . $conn->error;
    }
}

// Admin şifresini sıfırlama işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_admin_password'])) {
    $selectedAdminEmail = $_POST['selected_admin_email'];
    $newAdminPassword = $_POST['new_admin_password'];
    $confirmAdminPassword = $_POST['confirm_admin_password'];

    if ($newAdminPassword === $confirmAdminPassword) {
        $hashedPassword = password_hash($newAdminPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE admins SET password='$hashedPassword' WHERE email='$selectedAdminEmail'";

        if ($conn->query($sql) === TRUE) {
            $message = "Admin şifresi başarıyla sıfırlandı.";
        } else {
            $message = "Hata: " . $conn->error;
        }
    } else {
        $message = "Yeni şifreler eşleşmiyor.";
    }
}

// Admin silme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_admin'])) {
    $adminId = $_POST['admin_id'];

    $sql = "DELETE FROM admins WHERE id='$adminId'";
    if ($conn->query($sql) === TRUE) {
        $message = "Admin başarıyla silindi.";
    } else {
        $message = "Hata: " . $conn->error;
    }
}

// Tüm admin kullanıcılarını getirme
$adminUsers = [];
$sql = "SELECT * FROM admins";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $adminUsers[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - Kategoriler</title>
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
                <div class="search-bar">
                    <input type="text" placeholder="Search here...">
                </div>
                <div class="user-info">
                    <img src="profile.jpg" alt="Profile" class="profile-img">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
            <div class="content">
                <h2>Ayarlar</h2>
                <p><?php echo $message; ?></p>
                <form action="admin_settings.php" method="post">
                    <h3>Şifre Güncelle</h3>
                    <label for="new_password">Yeni Şifre:</label>
                    <input type="password" name="new_password" required>
                    <label for="confirm_password">Yeni Şifre (Tekrar):</label>
                    <input type="password" name="confirm_password" required>
                    <button type="submit" name="update_password">Güncelle</button>
                </form>
                <hr>
                <form action="admin_settings.php" method="post">
                    <h3>Yeni Admin Ekle</h3>
                    <label for="admin_username">Kullanıcı Adı:</label>
                    <input type="text" name="admin_username" required>
                    <label for="admin_email">E-Posta:</label>
                    <input type="email" name="admin_email" required>
                    <label for="admin_password">Şifre:</label>
                    <input type="password" name="admin_password" required>
                    <button type="submit" name="add_admin">Ekle</button>
                </form>
                <hr>
                <form action="admin_settings.php" method="post">
                    <h3>Admin Şifresini Sıfırla</h3>
                    <label for="selected_admin_email">Admin Seç:</label>
                    <select name="selected_admin_email" required>
                        <?php foreach ($adminUsers as $admin): ?>
                            <option value="<?php echo htmlspecialchars($admin['email']); ?>"><?php echo htmlspecialchars($admin['username']); ?> (<?php echo htmlspecialchars($admin['email']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <label for="new_admin_password">Yeni Şifre:</label>
                    <input type="password" name="new_admin_password" required>
                    <label for="confirm_admin_password">Yeni Şifre (Tekrar):</label>
                    <input type="password" name="confirm_admin_password" required>
                    <button type="submit" name="reset_admin_password">Şifreyi Sıfırla</button>
                </form>
                <hr>
                <h3>Adminleri Yönet</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Kullanıcı Adı</th>
                            <th>E-Posta</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($adminUsers as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            <td>
                                <form action="admin_settings.php" method="post" style="display:inline;">
                                    <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin['id']); ?>">
                                    <button type="submit" name="delete_admin" onclick="return confirm('Bu admini silmek istediğinizden emin misiniz?')">Sil</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
