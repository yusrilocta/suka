<?php include 'header.php' ?>
<!-- sdkjskdjskdjks -->
<div class="container">

<!-- Tambahkan tombol untuk menampilkan form tambah calon -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCalonModal">
    Tambah Calon
</button>

<!-- Tampilkan tabel dengan data calon -->
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Partai</th>
            <th>Nama Calon</th>
            <th>No. Urut</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Koneksi ke database
        include '../koneksi.php';

        // Fungsi untuk mendapatkan semua data calon
        function getCalon($conn) {
            $query = "SELECT * FROM calon";
            $stmt = $conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Fungsi untuk menambahkan calon baru
        function addCalon($conn, $nama_calon, $nama_partai, $no_urut) {

            $query = "INSERT INTO calon ( nama_calon, nama_partai, no_urut) VALUES ( :nama_calon, :nama_partai, :no_urut)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nama_calon', $nama_calon);
            $stmt->bindParam(':nama_partai', $nama_partai);
            $stmt->bindParam(':no_urut', $no_urut);
            $stmt->execute();
        }

        // Fungsi untuk menghapus calon berdasarkan ID
        function deleteCalon($conn, $id) {
            $query = "DELETE FROM calon WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }

        // Fungsi untuk mengupdate data calon
        function updateCalon($conn,$id, $nama_calon, $nama_partai, $no_urut) {
            $query = "UPDATE calon SET nama_calon = :nama_calon, nama_partai = :nama_partai, no_urut = :no_urut WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nama_calon', $nama_calon);
            $stmt->bindParam(':nama_partai', $nama_partai);
            $stmt->bindParam(':no_urut', $no_urut);
            $stmt->execute();
        }

        // Proses penambahan calon
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_calon'])) {
            $nama_calon = $_POST['nama_calon'];
            $nama_partai = $_POST['nama_partai'];
            $no_urut = $_POST['no_urut'];

            addCalon($conn, $nama_calon, $nama_partai, $no_urut);
            header("Location: calon.php");
            exit();
        }

        // Proses penghapusan calon
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_calon'])) {
            $id = $_POST['id'];

            deleteCalon($conn, $id);
            header("Location: calon.php");
            exit();
        }

        // Proses update calon
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_calon'])) {
            $id = $_POST['id'];
            $nama_calon = $_POST['nama_calon'];
            $nama_partai = $_POST['nama_partai'];
            $no_urut = $_POST['no_urut'];

            updateCalon($conn,$id, $nama_calon, $nama_partai, $no_urut);
            header("Location: calon.php");
            exit();
        }

        // Menampilkan data calon dalam tabel
        $calon = getCalon($conn);
        foreach ($calon as $data) {
     
            echo "<tr>";
            echo "<td>{$data['id']}</td>";
            echo "<td>{$data['nama_calon']}</td>";
            echo "<td>{$data['nama_partai']}</td>";
            echo "<td>{$data['no_urut']}</td>";
            echo "<td>";
            echo "<button type='button' class='mx-1 btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editCalonModal{$data['id']}'>Edit</button>";
            echo "<form method='POST' class='d-inline-block' onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus calon ini?');\">";
            echo "<input type='hidden' name='id' value='{$data['id']}'>";
            echo "<button type='submit' class='mx-1 btn btn-sm btn-danger' name='delete_calon'>Hapus</button>";

            echo "</td>";
            echo "</tr>";

            // Modal untuk edit calon
            echo "<div class='modal fade' id='editCalonModal{$data['id']}' tabindex='-1' aria-labelledby='editCalonModalLabel{$data['id']}' aria-hidden='true'>";
            echo "<div class='modal-dialog'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='editCalonModalLabel{$data['id']}'>Edit Calon</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<form method='POST'>";
            echo "<input type='hidden' name='id' value='{$data['id']}'>";
            echo "<div class='mb-3'>";
            echo "<label for='edit_id_partai{$data['id']}' class='form-label'>ID Partai</label>";
            echo "<input type='text' class='form-control' id='edit_nama_calon{$data['id']}' name='nama_calon' value='{$data['nama_calon']}' required>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label for='edit_nama{$data['id']}' class='form-label'>Nama Calon</label>";
            echo "<input type='text' class='form-control' id='edit_nama_partai{$data['id']}' name='nama_partai' value='{$data['nama_partai']}' required>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label for='edit_no_urut{$data['id']}' class='form-label'>No. Urut</label>";
            echo "<input type='text' class='form-control' id='edit_no_urut{$data['id']}' name='no_urut' value='{$data['no_urut']}' required>";
            echo "</div>";
            echo "<button type='submit' class='btn btn-primary' name='update_calon'>Simpan</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </tbody>
</table>

<!-- Modal untuk tambah calon -->
<div class="modal fade" id="addCalonModal" tabindex="-1" aria-labelledby="addCalonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCalonModalLabel">Tambah Calon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                <div class="mb-3">
                        <label for="nama" class="form-label">Nama Calon</label>
                        <input type="text" class="form-control" id="nama_calon" name="nama_calon" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Partai</label>
                        <input type="text" class="form-control" id="nama_partai" name="nama_partai" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_urut" class="form-label">No. Urut</label>
                        <input type="text" class="form-control" id="no_urut" name="no_urut" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_calon">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php' ?>