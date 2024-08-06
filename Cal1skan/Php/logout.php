<?php
session_start();
session_unset();
session_destroy();
setcookie('user', '', time() - 3600, '/'); // Çerezi silmek için
header("Location: ../index.php"); // Çıkış yaptıktan sonra anasayfaya yönlendir
exit();
?>
