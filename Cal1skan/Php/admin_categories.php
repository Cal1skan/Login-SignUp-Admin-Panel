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

// Yeni kategori ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $categoryTitle = $_POST['category_title'];
    $categoryFileName = $_POST['category_file_name'];

    // Profil fotoğrafı yükleme işlemi
    $targetDir = "category_images/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Dizin yoksa oluştur
    }
    $targetFile = $targetDir . basename($_FILES["category_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Dosya türü kontrolü
    $check = getimagesize($_FILES["category_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Dosya zaten var mı kontrolü
    if (file_exists($targetFile)) {
        $uploadOk = 0;
    }

    // Dosya boyutu kontrolü
    if ($_FILES["category_image"]["size"] > 500000) {
        $uploadOk = 0;
    }

    // Belirli dosya formatlarına izin verme
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO categories (title, file_name, image) VALUES ('$categoryTitle', '$categoryFileName', '$targetFile')";
            
            if ($conn->query($sql) === TRUE) {
                // Yeni kategori dosyasını oluştur
                $categoryFileDir = "categories/";
                if (!is_dir($categoryFileDir)) {
                    mkdir($categoryFileDir, 0777, true); // Dizin yoksa oluştur
                }
                $categoryFile = fopen($categoryFileDir . $categoryFileName . ".php", "w");
                $content = "<?php include '../login-header.php'; ?>\n<link rel=\"stylesheet\" href=\"../../Css/style.css\">\n";
                fwrite($categoryFile, $content);
                fclose($categoryFile);

                $message = "Yeni kategori başarıyla eklendi.";
            } else {
                $message = "Hata: " . $conn->error;
            }
        } else {
            $message = "Fotoğraf yüklenirken hata oluştu.";
        }
    } else {
        $message = "Fotoğraf yükleme kriterleri karşılanmadı.";
    }
}

// Kategori silme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_category'])) {
    $categoryId = $_POST['category_id'];
    $categoryFileName = $_POST['category_file_name'];

    // Veritabanından silme
    $sql = "DELETE FROM categories WHERE id='$categoryId'";
    if ($conn->query($sql) === TRUE) {
        // Kategori dosyasını silme
        $categoryFilePath = "categories/" . $categoryFileName . ".php";
        if (file_exists($categoryFilePath)) {
            unlink($categoryFilePath);
        }
        $message = "Kategori başarıyla silindi.";
    } else {
        $message = "Hata: " . $conn->error;
    }
}

// Kategori düzenleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_category'])) {
    $categoryId = $_POST['category_id'];
    $categoryTitle = $_POST['category_title'];
    $categoryFileName = $_POST['category_file_name'];
    $currentFileName = $_POST['current_file_name'];
    $currentImage = $_POST['current_image'];
    
    // Dosya adı değişirse eski dosyayı yeni adla değiştirme
    if ($categoryFileName != $currentFileName) {
        $currentFilePath = "categories/" . $currentFileName . ".php";
        $newFilePath = "categories/" . $categoryFileName . ".php";
        if (file_exists($currentFilePath)) {
            rename($currentFilePath, $newFilePath);
        }
    }

    // Yeni resim yükleme işlemi
    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] == 0) {
        $targetDir = "category_images/";
        $targetFile = $targetDir . basename($_FILES["category_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Dosya türü kontrolü
        $check = getimagesize($_FILES["category_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Dosya zaten var mı kontrolü
        if (file_exists($targetFile)) {
            $uploadOk = 0;
        }

        // Dosya boyutu kontrolü
        if ($_FILES["category_image"]["size"] > 500000) {
            $uploadOk = 0;
        }

        // Belirli dosya formatlarına izin verme
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $targetFile)) {
                $currentImage = $targetFile; // Yeni resim yolunu güncelle
            } else {
                $message = "Fotoğraf yüklenirken hata oluştu.";
            }
        } else {
            $message = "Fotoğraf yükleme kriterleri karşılanmadı.";
        }
    }

    // Veritabanında güncelleme
    $sql = "UPDATE categories SET title='$categoryTitle', file_name='$categoryFileName', image='$currentImage' WHERE id='$categoryId'";
    if ($conn->query($sql) === TRUE) {
        $message = "Kategori başarıyla güncellendi.";
    } else {
        $message = "Hata: " . $conn->error;
    }
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
                <h2>Kategoriler</h2>
                <p><?php echo $message; ?></p>
                <form action="admin_categories.php" method="post" enctype="multipart/form-data">
                    <h3>Yeni Kategori Ekle</h3>
                    <label for="category_title">Kategori Başlığı:</label>
                    <input type="text" name="category_title" required>
                    <label for="category_file_name">Dosya Adı:</label>
                    <input type="text" name="category_file_name" required>
                    <label for="category_image">Kategori Görseli:</label>
                    <input type="file" name="category_image" required>
                    <button type="submit" name="add_category">Ekle</button>
                </form>
                <hr>
                <h3>Mevcut Kategoriler</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Başlık</th>
                            <th>Dosya Adı</th>
                            <th>Görsel</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category['title']); ?></td>
                            <td><?php echo htmlspecialchars($category['file_name']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($category['image']); ?>" alt="Category Image" width="50" height="50"></td>
                            <td>
                                <form action="admin_categories.php" method="post" style="display:inline;">
                                    <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category['id']); ?>">
                                    <input type="hidden" name="category_file_name" value="<?php echo htmlspecialchars($category['file_name']); ?>">
                                    <button type="submit" name="delete_category" onclick="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')">Sil</button>
                                </form>
                                <button onclick="document.getElementById('editModal-<?php echo $category['id']; ?>').style.display='block'">Düzenle</button>
                                <div id="editModal-<?php echo $category['id']; ?>" class="modal">
                                    <div class="modal-content">
                                        <span class="close" onclick="document.getElementById('editModal-<?php echo $category['id']; ?>').style.display='none'">&times;</span>
                                        <form action="admin_categories.php" method="post" enctype="multipart/form-data">
                                            <h3>Kategori Düzenle</h3>
                                            <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category['id']); ?>">
                                            <input type="hidden" name="current_file_name" value="<?php echo htmlspecialchars($category['file_name']); ?>">
                                            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($category['image']); ?>">
                                            <label for="category_title">Kategori Başlığı:</label>
                                            <input type="text" name="category_title" value="<?php echo htmlspecialchars($category['title']); ?>" required>
                                            <label for="category_file_name">Dosya Adı:</label>
                                            <input type="text" name="category_file_name" value="<?php echo htmlspecialchars($category['file_name']); ?>" required>
                                            <label for="category_image">Yeni Kategori Görseli (isteğe bağlı):</label>
                                            <input type="file" name="category_image">
                                            <button type="submit" name="edit_category">Güncelle</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</body>
</html>
