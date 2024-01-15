<?php 
include '../../admin/koneksi.php';
include 'header.php';

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
?>
<div class="container">

        <table class="table table-striped" id="table table-striped datra">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="d-none d-sm-table-cell">TPS</th>
                    <th>Nama Calon</th>
                    <th class="d-none d-sm-table-cell">Partai</th>
                    <th class="d-none d-sm-table-cell">Suara SAH</th>

                </tr>
            </thead>
            <tbody>
                <?php

                // Fungsi untuk mendapatkan semua data pemilu
                function getPemilu($conn) {
                    $gg = $_SESSION['username'];
                    if (isset($_POST['filter']) && isset($_POST['filter_nama'])){
                        $filter_nama = $_POST['filter_nama'];
                        $query = "SELECT pemilu.id, pemilu.id_tps, tps.desa, tps.kec, tps.notps,pemilu.id_calon, calon.nama_calon,calon.nama_partai, pemilu.pemp, pemilu.sisa_suara, pemilu.bukti_file, pemilu.peml, pemilu.total, pemilu.handle    
                                    FROM pemilu
                                    INNER JOIN tps ON pemilu.id_tps = tps.id
                                    INNER JOIN calon ON pemilu.id_calon = calon.id
                                    WHERE calon.id = :filter_nama AND handle = :handle";
                        $stmt = $conn->prepare($query);
                        
                        $stmt->bindParam(':filter_nama', $filter_nama);
                        $stmt->bindParam(':handle', $gg);
                        $stmt->execute();
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $gg = $_SESSION['username'];
                        $query = "SELECT pemilu.id, pemilu.id_tps, tps.desa, tps.kec, tps.notps,pemilu.id_calon, calon.nama_calon,calon.nama_partai, pemilu.pemp, pemilu.bukti_file, pemilu.peml,tps.dpt, pemilu.total, pemilu.handle
                                    FROM pemilu
                                    INNER JOIN tps ON pemilu.id_tps = tps.id
                                    INNER JOIN calon ON pemilu.id_calon = calon.id
                                    where pemilu.handle = :handle";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':handle', $gg);
    $stmt->execute();
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
                    echo "<td class='d-none d-sm-table-cell'> Desa {$data['desa']}
                    <div class='small text-medium-emphasis'><span>Kecamatan {$data['kec']}</span> | TPS : {$data['notps']}</div>
                    </td>";
                    echo "<td >{$data['nama_calon']}</td>";
                    echo "<td class='d-none d-sm-table-cell'>{$data['nama_partai']}</td>";
                    echo "<td class='d-none d-sm-table-cell'>{$data['peml']}</td>";
                   # echo '<td><a href="' . $data['bukti_file'] . '" download><button class="btn btn-primary">Download Bukti File</button></a></td>';
                    echo "</tr>";  
                }
                ?>
            </tbody>
        </table>
    </div>
        
</div>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>

<?php include 'footer.php' ?>