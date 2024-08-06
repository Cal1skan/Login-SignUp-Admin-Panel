<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$adminUsername = $_SESSION['admin_username'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "caliskan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Gün içerisindeki yeni kullanıcıları getirme
$sqlNewUsers = "SELECT * FROM users WHERE registration_date >= CURDATE()";
$resultNewUsers = $conn->query($sqlNewUsers);

if (!$resultNewUsers) {
    die("Sorgu başarısız: " . $conn->error);
}

$newUsersCount = $resultNewUsers->num_rows;

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
                <div class="stats">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
                        <div class="stat-info">
                            <h3><?php echo $newUsersCount; ?></h3>
                            <p>New Users Today</p>
                        </div>
                    </div>
                </div>
                <div class="charts">
                    <div class="chart">
                        <h2>New Users Today</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Kullanıcı Adı</th>
                                    <th>E-Posta</th>
                                    <th>Şifre</th>
                                    <th>Kayıt Tarihi</th>
                                    <th>Profil Fotoğrafı</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($newUsersCount > 0) {
                                    while ($user = $resultNewUsers->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                                        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                                        echo "<td>" . htmlspecialchars($user['password']) . "</td>";
                                        echo "<td>" . htmlspecialchars($user['registration_date']) . "</td>";
                                        echo "<td><img src='" . htmlspecialchars($user['profile_picture']) . "' alt='Profile Picture' width='50' height='50'></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>Bugün yeni kullanıcı yok.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
