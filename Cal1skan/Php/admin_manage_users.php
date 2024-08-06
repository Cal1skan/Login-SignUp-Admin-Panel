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

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
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
                <h1>Kullanıcı Yönetimi</h1>
            </div>
            <div class="content">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kullanıcı Adı</th>
                            <th>E-Posta</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <a href="admin_edit_user.php?id=<?php echo $row['id']; ?>">Düzenle</a>
                                <a href="admin_delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">Sil</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
