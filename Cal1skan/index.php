<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "caliskan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mevcut kategorileri getirme
$categories = [];
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cal1skan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css">
    <script src="https://kit.fontawesome.com/baee8cbce1.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Css/style.css">
</head>
<body>

    <?php include 'Php/header.php'; ?>

    <div class="categories">

        <div class="category-list">
            <?php foreach ($categories as $category): ?>
                <div class="category-item">
                    <a href="categories/<?php echo htmlspecialchars($category['file_name']); ?>.php">
                        <img src="<?php echo htmlspecialchars($category['image']); ?>" alt="<?php echo htmlspecialchars($category['title']); ?>" width="100" height="100">
                        <p><?php echo htmlspecialchars($category['title']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="Js/script.js"></script>
</body>
</html>