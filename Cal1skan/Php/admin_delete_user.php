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

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $sql = "DELETE FROM users WHERE id='$userId'";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_manage_users.php");
        exit();
    } else {
        echo "Hata: " . $conn->error;
    }
} else {
    header("Location: admin_manage_users.php");
    exit();
}

$conn->close();
?>
