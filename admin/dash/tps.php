<?php include 'header.php' ?>

<div class="container mt-5">

<!-- Tambahkan tombol untuk menampilkan form tambah TPS -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addTpsModal">
    Tambah TPS
</button>
<button type="button" class="btn btn-primary mb-3" id="exportButton">
    Ekspor
</button>

<!-- Tampilkan tabel dengan data TPS -->
<table id="myTable" class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Kecamatan</th>
            <th>Tempat</th>
            <th>TPS</th>
            <th>Pemilih Laki-laki</th>
            <th>Pemilih Perempuan</th>
            <th>Dua Persen</th>
            <th>Total DPT</th>
            <th>dptb</th>
            <th>Suara Tak Sah</th>
            <th>dpk</th>
            <th>suara diterima</th>
            <th>suara digunakan</th>
            <th>suara Rusak</th>
            <th>tidak digunakan</th>
            <th>Handle</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Koneksi ke database
        include '../koneksi.php';

        // Fungsi untuk mendapatkan semua data TPS
        function getTps($conn) {
            $query = "SELECT * FROM tps";
            $stmt = $conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Fungsi untuk menambahkan TPS baru

        function addTps($conn, $kec, $desa, $notps, $peml, $pemp, $duaper, $dpt, $handle) {
            
            $query = "INSERT INTO tps ( kec,desa, notps, peml, pemp, duaper, dpt, handle ) VALUES (:kec, :desa, :notps, :peml, :pemp, :duaper,:dpt, :handle)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':kec', $kec);
            $stmt->bindParam(':desa', $desa);
            $stmt->bindParam(':notps', $notps);
            $stmt->bindParam(':peml', $peml);
            $stmt->bindParam(':pemp', $pemp);
            $stmt->bindParam(':duaper', $duaper);
            $stmt->bindParam(':dpt', $dpt);
            $stmt->bindParam(':handle', $handle);
            $stmt->execute();
        }

        // Fungsi untuk menghapus TPS berdasarkan ID
        function deleteTps($conn, $id) {
            $query = "DELETE FROM tps WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }

        // Fungsi untuk mengupdate data TPS
        function updateTps($conn,$id, $kec, $desa, $notps, $peml, $pemp, $duaper, $dpt, $handle) {
            $query = "UPDATE tps SET kec = :kec, desa = :desa,notps = :notps, peml = :peml, pemp = :pemp, duaper = :duaper, dpt = :dpt, handle = :handle WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':kec', $kec);
            $stmt->bindParam(':desa', $desa);
            $stmt->bindParam(':notps', $notps);
            $stmt->bindParam(':peml', $peml);
            $stmt->bindParam(':pemp', $pemp);
            $stmt->bindParam(':duaper', $duaper);
            $stmt->bindParam(':dpt', $dpt);
            $stmt->bindParam(':handle', $handle);
            $stmt->execute();
        }

        // Proses penambahan TPS
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_tps'])) {
            $kec = $_POST['kec'];
            $desa = $_POST['desa'];
            $notps = $_POST['notps'];
            $peml = $_POST['peml'];
            $pemp = $_POST['pemp'];
            $duaper = $_POST['duaper'];
            $dpt = $_POST['dpt'];
            $handle = $_POST['handle'];

            addTps($conn, $kec, $desa, $notps, $peml, $pemp, $duaper, $dpt, $handle);
            header("Location: juan.php?id=$handle");
            exit();
        }

        // Proses penghapusan TPS
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_tps'])) {
            $id = $_POST['id'];

            deleteTps($conn, $id);
            header("Location: tps.php");
            exit();
        }

        // Proses update TPS
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_tps'])) {
            $id = $_POST['id'];
            $kec = $_POST['kec'];
            $desa = $_POST['desa'];
            $notps = $_POST['notps'];
            $peml = $_POST['peml'];
            $pemp = $_POST['pemp'];
            $duaper = $_POST['duaper'];
            $dpt = $_POST['dpt'];

            if($duaper < 0){
                $duaper = 1;
            }
            $totaldpt = $peml + $pemp + $duaper;
            $handle = $_POST['handle'];

            updateTps($conn, $id, $kec, $desa, $notps, $peml, $pemp, $duaper, $dpt, $handle);
            header("Location: tps.php");
            exit();
        }
        function getUser($conn) {
            $kpps = "kpps";
            $query = "SELECT * FROM user where role = :kpps";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':kpps', $kpps);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        // Menampilkan data TPS dalam tabel
        $tps = getTps($conn);
        foreach ($tps as $data) {
            echo "<tr>";
            echo "<td>{$data['id']}</td>";
            echo "<td>{$data['kec']}</td>";
            echo "<td>{$data['desa']}</td>";
            echo "<td>{$data['notps']}</td>";
            echo "<td>{$data['peml']}</td>";
            echo "<td>{$data['pemp']}</td>";
            echo "<td>{$data['duaper']}</td>";
            echo "<td>{$data['dpt']}</td>";
            echo "<td>{$data['sisa_suara']}</td>";
            echo "<td>{$data['dptb']}</td>";
            echo "<td>{$data['dpk']}</td>";
            echo "<td>{$data['suara_diterima']}</td>";
            echo "<td>{$data['suara_digunakan']}</td>";
            echo "<td>{$data['suara_rusak']}</td>";
            echo "<td>{$data['suara_tak_terguna']}</td>";
            echo "<td>{$data['handle']}</td>";
            echo "<td>";
            echo "<button type='button' class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editTpsModal{$data['id']}'>Edit</button>";
            echo "<form method='POST' class='d-inline-block' onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus TPS ini?');\">";
            echo "<input type='hidden' name='id' value='{$data['id']}'>";
            echo "<button type='submit' class='btn btn-sm btn-danger' name='delete_tps'>Hapus</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";

            // Modal untuk edit TPS
            echo "<div class='modal fade' id='editTpsModal{$data['id']}' tabindex='-1' aria-labelledby='editTpsModalLabel{$data['id']}' aria-hidden='true'>";
            echo "<div class='modal-dialog'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='editTpsModalLabel{$data['id']}'>Edit TPS</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<form method='POST'>";
            echo "<input type='hidden' name='id' value='{$data['id']}'>";
            echo "<div class='mb-3'>";
            echo "<label for='edit_tempat{$data['id']}' class='form-label'>Tempat</label>";
            echo "<input type='text' class='form-control' id='edit_kec{$data['id']}' name='kec' value='{$data['kec']}' required>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label for='edit_kecamatan{$data['id']}' class='form-label'>Kecamatan</label>";
            echo "<input type='text' class='form-control' id='edit_desa{$data['id']}' name='desa' value='{$data['desa']}' required>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label for='edit_kecamatan{$data['id']}' class='form-label'>Kecamatan</label>";
            echo "<input type='text' class='form-control' id='edit_notps{$data['id']}' name='notps' value='{$data['notps']}' required>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label for='edit_peml{$data['id']}' class='form-label'>Pemilih Laki-laki</label>";
            echo "<input type='text' class='form-control' id='edit_peml{$data['id']}' name='peml' value='{$data['peml']}' required>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label for='edit_pemp{$data['id']}' class='form-label'>Pemilih Perempuan</label>";
            echo "<input type='text' class='form-control' id='edit_pemp{$data['id']}' name='pemp' value='{$data['pemp']}' required>";
            echo "</div>";
            echo "<div class='mb-3'>";
            echo "<label>Di Handle Oleh</label>";
            echo "<select for='handle' class='form-select' aria-label='Default select example' id='handle' name='handle' required>";
            $calon = getUser($conn);
            foreach ($calon as $dat)
            {
                
            echo "<option value='{$dat['nama']}'>{$dat['nama']}</option>";
                
            }
            echo "</select>";
            echo "</div>";
            echo "<button type='submit' class='btn btn-primary' name='update_tps'>Simpan</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </tbody>
</table>

<!-- Modal untuk tambah TPS -->
<div class="modal fade" id="addTpsModal" tabindex="-1" aria-labelledby="addTpsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTpsModalLabel">Tambah TPS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="tempat" class="form-label">Tempat</label>
                        <input type="text" class="form-control" id="kec" name="kec" required>
                    </div>
                    <div class="mb-3">
                        <label for="kecamatan" class="form-label">Desa</label>
                        <input type="text" class="form-control" id="desa" name="desa" required>
                    </div>
                    <div class="mb-3">
                        <label for="kecamatan" class="form-label">TPS</label>
                        <input type="text" class="form-control" id="notps" name="notps" required>
                    </div>
                    <div class="mb-3">
                        <label for="peml" class="form-label">Pemilih Laki-laki</label>
                        <input type="text" class="form-control" id="peml" name="peml" required>
                    </div>
                    <div class="mb-3">
                        <label for="pemp" class="form-label">Pemilih Perempuan</label>
                        <input type="text" class="form-control" id="pemp" name="pemp" required>
                    </div>
                    <div class="mb-3">
                        <label for="pemp" class="form-label">Total DPT</label>
                        <input type="text" class="form-control" id="dpt" name="dpt" required>
                    </div>
                    <div class="mb-3">
                        <label for="pemp" class="form-label">Suara Cadangan</label>
                        <input type="text" class="form-control" id="duaper" name="duaper" required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Di Handle Oleh</label>
                    <select for="handle" class="form-select" aria-label="Default select example" id="handle" name="handle" required>
                        <?php
                        $calon = getUser($conn);
                        foreach ($calon as $dat)
                        {
                        echo "<option value='{$dat['username']}'> {$dat['username']} </option>";
                        }?>
                    </select>
                        </div>
                        <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="add_tps">Simpan</button>
                        </div>
                    
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
        for (var j = 0; j < 16; j++) {
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