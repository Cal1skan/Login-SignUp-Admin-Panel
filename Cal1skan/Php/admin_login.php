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

    $servername = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "caliskan";

    // Create connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['email'];
            $_SESSION['admin_username'] = $admin['username'];

            // Log kaydetme
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $timestamp = date('Y-m-d H:i:s');
            $adminId = $admin['id'];

            $logSql = "INSERT INTO admin_logs (admin_id, ip_address, user_agent, timestamp) VALUES ('$adminId', '$ipAddress', '$userAgent', '$timestamp')";
            $conn->query($logSql);

            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Geçersiz şifre.";
        }
    } else {
        $error = "Geçersiz email.";
    }
    $conn->close();

}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş</title>
    <link rel="stylesheet" href="../Css/admin_login.css">
</head>
<body>
    <div class="container">
        <h2>Admin Giriş</h2>
        <?php if ($message != "") { echo "<p class='message'>$message</p>"; } ?>
        <form action="admin_login.php" method="POST">
            <input type="email" name="email" placeholder="E-Posta Adresinizi Giriniz" required>
            <input type="password" name="password" placeholder="Şifrenizi Giriniz" required>
            <button type="submit">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
