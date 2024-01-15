<?php
// Koneksi ke database
$host = 'localhost';
$db_name = 'admin';
$db_user = 'root';
$db_pass = '';

$conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>