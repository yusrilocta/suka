<!-- crud.php -->
<?php

include 'header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CRUD - User Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">

        <!-- Tambahkan tombol untuk menampilkan form tambah user -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Tambah Admin / KPPS
        </button>
        <button type="button" class="btn btn-primary mb-3" id="exportButton">
    Ekspor
</button>
        <!-- Tampilkan tabel dengan data user -->
        <table id="myTable"  class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Nomer Induk Kependudukan</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../koneksi.php';
                // Fungsi untuk mendapatkan semua data user
                function getUser($conn) {
                    $kpps = "kpps";
                    $query = "SELECT * FROM user where role = :kpps";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':kpps', $kpps);
                    $stmt->execute();
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                // Fungsi untuk menambahkan user baru
                function addUser($conn, $id_user, $nama, $nik, $role,$username, $pass) {
                    $query = "INSERT INTO user (id, nama, nik, role, username, password) VALUES (:id_user, :nama, :nik, :role, :username :password)";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':id_user', $id_user);
                    $stmt->bindParam(':nama', $nama);
                    $stmt->bindParam(':nik', $nik);
                    $stmt->bindParam(':role', $role);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $pass);
                    $stmt->execute();
                }

                // Fungsi untuk menghapus user berdasarkan ID
                function deleteUser($conn, $id) {
                    $query = "DELETE FROM user WHERE id = :id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                }

                // Fungsi untuk mengupdate data user
                function updateUser($conn, $id, $nama, $nik, $role, $username, $pass) {
                    $query = "UPDATE user SET nama = :nama, nik = :nik, username = :username, password = :password, role = :role WHERE id = :id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':nama', $nama);
                    $stmt->bindParam(':nik', $nik);
                    $stmt->bindParam(':role', $role);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $pass);
                    $stmt->execute();
                }

                // Proses penambahan user
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
                    $id = $_POST['id'];
                    $nama = $_POST['nama'];
                    $nik = $_POST['nik'];
                    $role = $_POST['role'];
                    $username = $_POST['username'];
                    $pass = $_POST['password'];
                    updateUser($conn, $id, $nama, $nik, $role,$username, $pass);
                    header("Location: user.php");
                    exit();
                }

                // Proses penghapusan user
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
                    $id = $_POST['id'];

                    deleteUser($conn, $id);
                    header("Location: crud.php");
                    exit();
                }

                // Proses update user
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
                    $id = $_POST['id'];
                    $nama = $_POST['nama'];
                    $nik = $_POST['nik'];
                    $role = $_POST['role'];
                    $username = $_POST['username'];
                    $pass = $_POST['password'];
                    updateUser($conn, $id, $nama, $nik, $role,$username, $pass);
                    header("Location: user.php");
                    exit();
                }

                // Menampilkan data user dalam tabel
                $users = getUser($conn);
                foreach ($users as $data) {
                    echo "<tr>";
                    echo "<td>{$data['id']}</td>";
                    echo "<td>{$data['nama']}</td>";
                    echo "<td>{$data['nik']}</td>";
                    echo "<td>{$data['role']}</td>";
                    echo "<td>";
                    echo "<button type='button' class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editUserModal{$data['id']}'>Edit</button>";
                    echo "<form method='POST' class='d-inline-block' onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus user ini?');\">";
                    echo "<input type='hidden' name='id' value='{$data['id']}'>";
                    echo "<button type='submit' class='btn btn-sm btn-danger' name='delete_user'>Hapus</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";

                    // Modal untuk edit user
                    echo "<div class='modal fade' id='editUserModal{$data['id']}' tabindex='-1' aria-labelledby='editUserModalLabel{$data['id']}' aria-hidden='true'>";
                    echo "<div class='modal-dialog'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='editUserModalLabel{$data['id']}'>Edit User</h5>";
                    echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
                    echo "<form method='POST'>";

                    echo "<input type='hidden' name='id' value='{$data['id']}'>";

                    echo "<div class='mb-3'>";
                    echo "<label for='edit_nama{$data['id']}' class='form-label'>username</label>";
                    echo "<input type='text' class='form-control' id='edit_username{$data['id']}' name='username' value='{$data['username']}' required>";
                    echo "</div>";
                    
                    echo "<div class='mb-3'>";
                    echo "<label for='edit_nama{$data['id']}' class='form-label'>Nama</label>";
                    echo "<input type='text' class='form-control' id='edit_nama{$data['id']}' name='nama' value='{$data['nama']}' required>";
                    echo "</div>";

                    echo "<div class='mb-3'>";
                    echo "<label for='edit_nik{$data['id']}' class='form-label'>NIK</label>";
                    echo "<input type='text' class='form-control' id='edit_nik{$data['id']}' name='nik' value='{$data['nik']}' required>";
                    echo "</div>";

                    echo "<div class='mb-3'>";
                    echo "<label for='edit_role{$data['id']}' class='form-label'>Role</label>";
                    echo "<select class='form-select' id='edit_role{$data['id']}' name='role' required>";
                    echo "<option value='kpps'" . (($data['role'] == 'kpps') ? ' selected' : '') . ">KPPS</option>";
                    echo "<option value='admin'" . (($data['role'] == 'admin') ? ' selected' : '') . ">Admin</option>";
                    echo "</select>";
                    echo "</div>";                    
                    echo "<div class='mb-3'>";
                    echo "<label for='edit_password{$data['id']}' class='form-label'>Password</label>";
                    echo "<input type='text' class='form-control' id='edit_password{$data['id']}' name='password' value='{$data['password']}' required>";
                    echo "</div>";
                    echo "<button type='submit' class='btn btn-primary' name='update_user'>Simpan</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </tbody>
        </table>

        <!-- Modal untuk tambah user -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                        <div class="mb-3">
                                <label for="id_user" class="form-label">WhatsApp</label>
                                <input type="text" class="form-control" id="id_user" name="id_user" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">username "NAMA PENDEK TANPA SPASI"</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="nik" class="form-label">Nomor Induk Keluarga</label>
                                <input type="text" class="form-control" id="nik" name="nik" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="kpps">KPPS</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">password</label>
                                <input type="text" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_user">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
  document.getElementById("exportButton").addEventListener("click", function() {
    // Mendapatkan tabel HTML
    var table = document.getElementById("myTable");
    var rows = table.rows;

    var csvContent = "data:text/csv;charset=utf-8,";

    // Mengambil data hanya dari kolom-kolom yang ingin disertakan dalam CSV
    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].cells;
        var rowData = [];

        // Hanya ambil data dari kolom pertama sampai kolom ke-4 (sesuaikan dengan kebutuhan Anda)
        for (var j = 0; j < 3; j++) {
            rowData.push(cells[j].textContent);
        }

        // Gabungkan data dalam satu baris CSV
        csvContent += rowData.join(",") + "\n";
    }

    // Membuat tautan untuk mengunduh file CSV
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "data.csv");
    document.body.appendChild(link);

    // Mengklik tautan untuk mengunduh file CSV
    link.click();
});
</script>
    <?php include 'footer.php' ?>