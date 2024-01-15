<?php include 'header.php';
include '../koneksi.php';

// Proses import data dari file CSV
if (isset($_POST['submit'])) {
    if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file_tmp_path, 'r');

        // Skip header row
        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            $id_tps = $data[0];
            $kec = $data[1];
            $desa = $data[2];
            $notps = $data[3];
            $peml = $data[4];
            $pemp = $data[5];
            $duaper = $data[6];
            $id_calon = $data[7];
            $nama_calon = $data[8];
            $nama_partai = $data[9];
            $no_urut = $data[10];
            $tpl = $data[11];
            $tpp = $data[12];
            $tdua = $data[13];
            $id_pemilu = $data[14];

                

                // Insert data ke dalam tabel "calon"
                $querysatu = "
                INSERT INTO tps (id, kec, desa, notps, peml, pemp, duaper) VALUES (:id_tps, :kec, :desa, :notps, :peml, :pemp, :duaper) ON DUPLICATE KEY UPDATE kec = VALUES(kec), desa = VALUES(desa), notps = VALUES(notps), peml = VALUES(peml), pemp = VALUES(pemp), duaper = VALUES(duaper);
                INSERT INTO calon (id, nama_partai, nama_calon, no_urut) VALUES (:id_calon, :nama_partai, :nama_calon, :no_urut) ON DUPLICATE KEY UPDATE nama_partai = VALUES(nama_partai), nama_calon = VALUES(nama_calon), no_urut = VALUES(no_urut);
                INSERT INTO pemilu (id, id_calon, id_tps, peml, pemp, total) VALUES (:id_pemilu, :id_calon, :id_tps, :pemlss, :pempss, :total) ON DUPLICATE KEY UPDATE id_calon = VALUES(id_calon), id_tps = VALUES(id_tps), peml = VALUES(peml), pemp = VALUES(pemp), peml = VALUES(total);";
                $stmt = $conn->prepare($querysatu);
                $stmt->bindParam(':id_tps', $id_tps);
                $stmt->bindParam(':kec', $kec);
                $stmt->bindParam(':desa', $desa);
                $stmt->bindParam(':notps', $notps);
                $stmt->bindParam(':peml', $peml);
                $stmt->bindParam(':pemp', $pemp);
                $stmt->bindParam(':duaper', $duaper);
                $stmt->bindParam(':id_calon', $id_calon);
                $stmt->bindParam(':nama_calon', $nama_calon);
                $stmt->bindParam(':nama_partai', $nama_partai);
                $stmt->bindParam(':no_urut', $no_urut);
                $stmt->bindParam(':pemlss', $tpl);
                $stmt->bindParam(':pempss', $tpp);
                $stmt->bindParam(':total', $tdua);
                $stmt->bindParam(':id_pemilu', $id_pemilu);

            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Terjadi kesalahan: " . $e->getMessage();
                break;
            }
        }

        fclose($handle);
        echo "Data berhasil diimpor dari file CSV.";
    } else {
        echo "Terjadi kesalahan saat mengunggah file CSV.";
    }
}
$quera = "SELECT SUM(tps.dpt) AS total_dpt
FROM tps";

$stmt = $conn->query($quera);
$dats = $stmt->fetchAll(PDO::FETCH_ASSOC);

$quers = "SELECT SUM(pemilu.peml) AS total
FROM pemilu";

$stmt = $conn->query($quers);
$datd = $stmt->fetchAll(PDO::FETCH_ASSOC);

$quers = "SELECT COUNT(*) AS jumlah_data FROM tps";

$stmt = $conn->query($quers);
$daty = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * FROM calon";
$stmt = $conn->query($query);
$calon = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



    <div class="container mt-4">
    <button type="button" class="btn btn-primary mb-3" id="exportButton">
    Ekspor
</button>
    <div class="row mb-4 mx-auto">
        <div class="col mt-2">
        <div class="card text-center w-100" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Daftar Pemilih Tetap</h5>
    <?php foreach ($dats as $row) { ?>
    <h1 class="card-text"><?php echo $row['total_dpt']; ?></h1>
    <?php $dtlk = $row['total_dpt']; } ?>
  </div>
</div>
        </div>
        <div class="col mt-2">
        <div class="card text-center w-100" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Total TPS</h5>
    <?php foreach ($daty as $row) { ?>
    <h1 class="card-text"><?php echo $row['jumlah_data']; ?></h1>
    <?php $ttlk = '0'; } ?>
  </div>
</div>
        </div>
        <div class="col mt-2">
        <div class="card text-center w-100" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Pemilih</h5>
    <?php foreach ($datd as $row) { ?>
    <h1 class="card-text"><?php echo $row['total']; ?></h1>
    <?php $ttpp = $row['total']; } ?>
  </div>
</div>
        </div>
    </div>
<!-- ASLAKSLAKSLA -->
<?php 
$ttpt = $ttpp + $ttlk;
$ttdt = $dtlk;
if($ttpt < $ttdt){ ?>
    <div class="alert alert-success text-center" role="alert">
    Quick Count Dinilai Layak
</div>
<?php
} else { ?>
<div class="alert alert-danger text-center" role="alert">
  DATA TIDAK LAYAK
</div>
<?php } ?>
        <!-- Tambahkan tombol untuk menampilkan form tambah pemilu -->
<div class="container">
    <div class="row">
        <!-- <div class="col-md-2 col-6 mb-3">  
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPemiluModal">
                Tambah Data
            </button>
        </div>
        <div class="col-md-2 col-6 mb-3"> 
            <button type="button" class="btn btn-primary" onclick="document.location='copypaste_excel.php'">
                Paste Data
            </button>
        </div> -->
        <!-- <div class="col-md-4 col-12 mb-3">
            <form method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="file" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2" name="csv_file">
                    <button class="btn btn-outline-secondary" type="submit" name="submit" id="button-addon2">Tambah Data</button>
                </div>
            </form>
        </div> -->
        <div class="col-md-4 col-12 mb-3"> <!-- Kolom keempat -->
            <form method="POST">
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example" name="filter_nama">
                        <option selected>Pilih Nama Calon</option>
                        <?php
                        foreach ($calon as $data) {
                        ?>
                        <option value="<?php echo $data['id']?>"><?php echo $data['nama_calon']?></option>
                        <?php } ?>
                    </select>
                    <button class="btn btn-outline-secondary" type="submit" name="filter" id="button-filter">Pilih Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

        <!-- Tampilkan tabel dengan data pemilu -->
        <table id="myTable" class="table table-striped" id="table table-striped datra">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="d-none d-sm-table-cell">TPS</th>
                    <th>Nama Calon</th>
                    <th class="d-none d-sm-table-cell">Partai</th>
                    <th class="d-none d-sm-table-cell">Pemilih Sah</th>
                    <th>Total</th>
                    <!-- <th>Aksi</th> -->
                </tr>
            </thead>
            <tbody>
                <?php

                // Fungsi untuk mendapatkan semua data pemilu
                function getPemilu($conn) {
                    if (isset($_POST['filter']) && isset($_POST['filter_nama'])){
                        $filter_nama = $_POST['filter_nama'];
                        $query = "SELECT pemilu.id, pemilu.id_tps, tps.desa, tps.kec, tps.notps,pemilu.id_calon, calon.nama_calon,calon.nama_partai, pemilu.pemp, pemilu.peml,total
                                    FROM pemilu
                                    INNER JOIN tps ON pemilu.id_tps = tps.id
                                    INNER JOIN calon ON pemilu.id_calon = calon.id
                                    WHERE calon.id = :filter_nama"; // Gunakan parameter untuk menghindari SQL injection
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':filter_nama', $filter_nama, PDO::PARAM_INT); // Sesuaikan tipe datanya dengan kolom di database
                        $stmt->execute();
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $query = "SELECT pemilu.id, pemilu.id_tps, tps.desa, tps.kec, tps.notps, pemilu.id_calon, calon.nama_calon,calon.nama_partai, pemilu.pemp, pemilu.peml,total
                                    FROM pemilu
                                    INNER JOIN tps ON pemilu.id_tps = tps.id
                                    INNER JOIN calon ON pemilu.id_calon = calon.id";
                        $stmt = $conn->query($query);
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                }
                

                // Fungsi untuk menambahkan pemilu baru
                function addPemilu($conn, $id_tps, $id_calon, $peml, $pemp) {
                    $total = $peml + $pemp;
                    $query = "INSERT INTO pemilu (id_tps, id_calon, peml, pemp, total) VALUES (:id_tps, :id_calon, :peml, :pemp, $total)";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':id_tps', $id_tps);
                    $stmt->bindParam(':id_calon', $id_calon);
                    $stmt->bindParam(':peml', $peml);
                    $stmt->bindParam(':pemp', $pemp);
                    $stmt->execute();
                }

                // Fungsi untuk menghapus pemilu berdasarkan ID
                function deletePemilu($conn, $id) {
                    $query = "DELETE FROM pemilu WHERE id = :id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                }

                // Fungsi untuk mengupdate data pemilu
                function updatePemilu($conn, $id, $id_tp, $id_calo , $peml, $pemp) {
                    $total = $peml + $pemp;
                    $query = "UPDATE pemilu SET id_tps = :id_tps, id_calon = :id_calon, peml = :peml, pemp = :pemp, total = $total WHERE id = :id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':id_tps', $id_tp);
                    $stmt->bindParam(':id_calon', $id_calo);
                    $stmt->bindParam(':peml', $peml);
                    $stmt->bindParam(':pemp', $pemp);
                    $stmt->execute();
                }

                // Proses penambahan pemilu
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pemilu'])) {
                    $id_tps = $_POST['id_tps'];
                    $id_calon = $_POST['id_calon'];
                    $peml = $_POST['peml'];
                    $pemp = $_POST['pemp'];

                    addPemilu($conn, $id_tps, $id_calon, $peml, $pemp);
                    header("Location: pemilu.php");
                    exit();
                }

                // Proses penghapusan pemilu
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_pemilu'])) {
                    $id = $_POST['id'];

                    deletePemilu($conn, $id);
                    header("Location: pemilu.php");
                    exit();
                }

                // Proses update pemilu
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pemilu'])) {
                    $id = $_POST['id'];
                    $id_tp = $_POST['edit_id_tps'];
                    $id_calo = $_POST['edit_id_calon'];
                    $peml = $_POST['peml'];
                    $pemp = $_POST['pemp'];
                    $total = $peml +$pemp;

                    updatePemilu($conn, $id, $id_tp, $id_calo, $peml, $pemp, $total);
                    header("Location: pemilu.php");
                    exit();
                }

                // Menampilkan data pemilu dalam tabel
                $pemilu = getPemilu($conn);
                foreach ($pemilu as $data) {
                    echo "<tr id='datras'>";
                    echo "<td>{$data['id']}</td>";
                    echo "<td class='d-none d-sm-table-cell'>{$data['kec']} Desa {$data['desa']} No TPS : {$data['notps']}</td>";
                    echo "<td >{$data['nama_calon']}</td>";
                    echo "<td class='d-none d-sm-table-cell'>{$data['nama_partai']}</td>";
                    echo "<td class='d-none d-sm-table-cell'>{$data['peml']}</td>";
                    echo "<td>{$data['total']}</td>";
                    // echo "<td>";
                    // echo "<button type='button' class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editPemiluModal{$data['id']}'>Edit</button>";
                    // echo "<form method='POST' class='d-inline-block' onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus pemilu ini?');\">";
                    // echo "<input type='hidden' name='id' value='{$data['id']}'>";
                    // echo "<button type='submit' class='btn btn-sm btn-danger' name='delete_pemilu'>Hapus</button>";
                    // echo "</form>";
                    // echo "</td>";
                    echo "</tr>";

                    // Modal untuk edit pemilu
                    echo "<div class='modal fade' id='editPemiluModal{$data['id']}' tabindex='-1' aria-labelledby='editPemiluModalLabel{$data['id']}' aria-hidden='true'>";
                    echo "<div class='modal-dialog'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='editPemiluModalLabel{$data['id']}'>Edit Pemilu</h5>";
                    echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='id' value='{$data['id']}'>";
                    echo "<div class='mb-3'>";
                    echo "<label for='edit_id_tps{$data['id']}' class='form-label'>ID TPS</label>";
                    echo "<input type='text' class='form-control' id='edit_id_tps{$data['id']}' name='edit_id_tps' value='{$data['id_tps']}'>";
                    echo "</div>";
                    echo "<div class='mb-3'>";
                    echo "<label for='edit_id_calon{$data['id']}' class='form-label'>ID Calon</label>";
                    echo "<input type='text' class='form-control' id='edit_id_calon{$data['id']}' name='edit_id_calon' value='{$data['id_calon']}'>";
                    echo "</div>";
                    echo "<div class='mb-3'>";
                    echo "<label for='edit_peml{$data['id']}' class='form-label'>Pemilih</label>";
                    echo "<input type='text' class='form-control' id='edit_peml{$data['id']}' name='peml' value='{$data['peml']}' required>";
                    echo "</div>";
                    echo "<div class='mb-3'>";
                    echo "<label for='edit_pemp{$data['id']}' class='form-label'>Pemilihan</label>";
                    echo "<input type='text' class='form-control' id='edit_pemp{$data['id']}' name='pemp' value='{$data['pemp']}' required>";
                    echo "</div>";
                    echo "<button type='submit' class='btn btn-primary' name='update_pemilu'>Simpan</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </tbody>
        </table>

        <!-- Modal untuk tambah pemilu -->
        <?php

                     $query = "SELECT * FROM tps";
                     $stmt = $conn->query($query);
                     $tps = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    ?>
        <div class='modal fade' id='addPemiluModal' tabindex='-1' aria-labelledby='addPemiluModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='addPemiluModalLabel'>Tambah Pemilu</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body'>
                        <form method='POST'>
                        <div class='mb-3'>
                            <label for="id_tps" class="form-label">Pilih TPS</label>
                            <select for="id_tps" class="form-select" aria-label="Default select example" id="id_tps" name="id_tps" required>
                            
                            <?php
                                foreach ($tps as $data) {
                                ?>
                                <option value="<?php echo $data['id']?>"><?php echo $data['kec'].' Desa : '.$data['desa'].' TPS : '.$data['notps']?></option>
                                <?php } ?>
                            </select>
                            </div>
                            <div class='mb-3'>
                            <label for="id_calon" class="form-label">Pilih Calon</label>
                            <select for="id_calon" class="form-select" aria-label="Default select example" id="id_calon" name="id_calon" required>
                            <?php
                                foreach ($calon as $data) {
                                ?>
                                <option value="<?php echo $data['id']?>"><?php echo $data['nama_calon']?></option>

                                <?php } ?>

                            </select>
                            </div>
<?php                            foreach ($calon as $data) { ?>
                            <input type='text' value="<?php echo $data['nama_partai']?>" class='form-control' id='nama_partai' name='nama_partai' required hidden>
    
                            <?php } ?>
                            <div class='mb-3'>
                                <label for='peml' class='form-label'>Pemilih</label>
                                <input type='text' class='form-control' id='peml' name='peml' required>
                            </div>
                            <div class='mb-3'>
                                <label for='pemp' class='form-label'>Pemilihan</label>
                                <input type='text' class='form-control' id='pemp' name='pemp' required>
                            </div>
                            <button type='submit' class='btn btn-primary' name='add_pemilu'>Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
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
        for (var j = 0; j < 5; j++) {
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