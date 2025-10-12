<?php
include 'config.php';
$username = 'Eduwisata Taro';
$password = password_hash('Gianyar1', PASSWORD_DEFAULT);
mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
echo "User admin berhasil ditambah!";
?>
