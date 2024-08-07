<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$loggedIn = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cal1skan - Anasayfa</title>
  <link rel="stylesheet" href="../Css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css">

<script src="https://kit.fontawesome.com/baee8cbce1.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="header">
    <div class="header-ust">
      <div class="logo">
        <img src="../assets/White.png" alt="" width="80vw" height="80vw">
      </div>
      <div class="header-ust-sag">
        <ul>
          <li><a href=""><i class="fa-solid fa-key"></i></a></li>
          <li class="dropdown">
            <a href="#" class="dropbtn"><i class="fa-solid fa-bell"></i></a>
            <div class="dropdown-content">
              <ul>
                <li><a href="">Bildiri Yok</a></li>
              </ul>
            </div>
          </li>
          <li class="dropdown">
            <a href="#" class="dropbtn">
              <?php if ($loggedIn && !empty($profilePicture)): ?>
                <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profil Fotoğrafı" style="width: 40px; height: 40px; border-radius: 50%;">
              <?php else: ?>
                <i class="fa-solid fa-user-secret"></i>
              <?php endif; ?>
            </a>
            <div class="dropdown-content">
              <ul>
                <?php if ($loggedIn): ?>
                  <li><a href="profile.php"><?php echo htmlspecialchars($userEmail); ?></a></li>
                  <li><a href="logout.php">Çıkış Yap</a></li>
                <?php else: ?>
                  <li><a href="login.php">Giriş Yap / Kayıt Ol</a></li>
                <?php endif; ?>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <div class="header-alt">
      <ul>
        <li><a href="index.php">Anasayfa</a></li>
        <li class="dropdown">
          <a href="#" class="dropbtn">Hizmetler</a>
          <div class="dropdown-content">
            <a href="#">Hizmet 1</a>
            <a href="#">Hizmet 2</a>
            <a href="#">Hizmet 3</a>
          </div>
        </li>
        <li><a href="#">Kurallar</a></li>
      </ul>
    </div>
  </div>
</body>
</html>
