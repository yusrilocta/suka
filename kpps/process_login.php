<!-- process_login.php -->
<?php
session_start();

include 'koneksi.php';
// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $role = "kpps";
    $password = $_POST['password'];

        // Query untuk memeriksa apakah username dan password sesuai
    $query = "SELECT * FROM user WHERE role = :role AND username = :username AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Login berhasil
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header("Location: kpps/index.php"); // Ganti dengan halaman dashboard yang sesuai
        exit();
    } else {
        // Login gagal
        header("Location:../index.php.php?error=1");
        exit();
    }
}
?>
