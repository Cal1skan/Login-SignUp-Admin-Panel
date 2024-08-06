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

$adminUsername = "Cal1skan";
$adminEmail = "asgcal1skan@gmail.com";
$adminPassword = "123";
$hashedPassword = password_hash($adminPassword, PASSWORD_BCRYPT);

$sql = "INSERT INTO admins (username, email, password) VALUES ('$adminUsername', '$adminEmail', '$hashedPassword')";

if ($conn->query($sql) === TRUE) {
    echo "Admin kullanıcı başarıyla oluşturuldu.";
} else {
    echo "Hata: " . $conn->error;
}

$conn->close();
?>
