<?php include 'header.php';
include '../koneksi.php';

$gg = $_SESSION['username'];
$sql = "SELECT * FROM tps WHERE handle = '$gg'"; 
$result = $conn->query($sql);
    while ($row = $result->fetch()) {
        $id_tps = $row['id'];
        $kec = $row['kec'];
        $desa = $row['desa'];
        $notps = $row['notps'];
        $dpt = $row['dpt'];
        $dptb = $row['dptb'];
        $dpk = $row['dpk'];
        $suara_diterima = $row['suara_diterima'];
        $suara_digunakan = $row['suara_digunakan'];
        $suara_rusak = $row['suara_rusak'];
        $suara_tak_terguna = $row['suara_tak_terguna'];
} ?>
    <div class="container mt-5">
        <form action="pros_input_sisa.php" method="post" enctype="multipart/form-data">
            <h3>Pengguna Hak Pilih</h3>
            <div class="form-group">
                <label for="jumlah_dpt">Jumlah DPT:</label>
                <input type="number" class="form-control" id="jumlah_dpt" name="dpt" value="<?php echo $dpt ?>" readonly>
            </div>
            <div class="form-group">
                <label for="jumlah_dptb">Jumlah DPTB:</label>
                <input type="number" class="form-control" id="jumlah_dptb" name="dptb" value="<?php echo $dptb ?>" readonly>
            </div>
            <div class="form-group">
                <label for="jumlah_dpk">Jumlah DPK:</label>
                <input type="number" class="form-control" id="jumlah_dpk" name="dpk" value="<?php echo $dpk ?>" required>
            </div>

            <h3>Pengguna Surat Suara</h3>
            <div class="form-group">
                <label for="suara_diterima">Suara Diterima:</label>
                <input type="number" class="form-control" id="suara_diterima" name="suara_diterima" value="<?php echo $suara_diterima ?>" required>
            </div>
            <div class="form-group">
                <label for="suara_digunakan">Suara Digunakan:</label>
                <input type="number" class="form-control" id="suara_digunakan" name="suara_digunakan" value="<?php echo $suara_digunakan ?>" required>
            </div>
            <div class="form-group">
                <label for="suara_rusak">Suara Rusak:</label>
                <input type="number" class="form-control" id="suara_rusak" name="suara_rusak" value="<?php echo $suara_rusak ?>" required>
            </div>
            <div class="form-group">
                <label for="suara_tidak_digunakan">Suara Tidak Digunakan:</label>
                <input type="number" class="form-control" id="suara_tak_terguna" name="suara_tak_terguna" value="<?php echo $suara_tak_terguna ?>" required>
            </div>

            
            
            <?php
                    function getTpsData($conn)
                    {
                        $gg = $_SESSION['username'];
                        $query = "SELECT * FROM tps WHERE handle = '$gg'"; 
                        $stmt = $conn->query($query);
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
            
                    // Fungsi untuk mendapatkan data calon dari tabel "calon"
                    function getCalonData($conn)
                    {
                       
                        $query = "SELECT * FROM calon";
                        $stmt = $conn->query($query);
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
            
                    function getPemiluData($conn)
                    {
                        $query = "SELECT * FROM pemilu";
                        $stmt = $conn->query($query);
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }

                    $pemiluData = getPemiluData($conn);
                    $tpsData = getTpsData($conn);
                    $calonData = getCalonData($conn);

                    foreach ($tpsData as $data) {
                        echo "<h3> Kec : {$data['kec']} Desa : {$data['desa']} Tps : {$data['notps']}</h3>";
                        echo "<input type='text' class='form-control' value='{$data['id']}' name='id_tps'>";
                        foreach ($calonData as $calon) {
                            
                            ?>
            <div class="form-group">
                <label for="calon1_suara_sah"><?php echo $calon['nama_calon'] ?> Suara Sah:</label>
                <input type="hidden" class="form-control" id="calon1_suara_sah" value='<?php echo $calon['id']?>' name='id_calon[]'>
                <input type="number" class="form-control" id="calon1_suara_sah" name='peml[]' required>
            </div>
                            <?php
                        }
                    }
                    ?>

            <h3>Form Suara tak SAH</h3>
            <div class="form-group">
                <label for="suara_tidak_sah">Suara Tidak Sah:</label>
                <input type="number" class="form-control" id="suara_tidak_sah" name="sisa_suara" required>
            </div>

            <h3>Upload File</h3>
            <div class="form-group">
                <label for="bukti_file">Upload halaman 1 :</label>
                <input type="file" class="form-control-file" name="bukti_file" accept=".jpg" required>
                <div class="invalid-feedback">Pilih file JPG.</div>
            </div>
            <div class="form-group">
                <label for="bukti_file">Upload Halaman 2 :</label>
                <input type="file" class="form-control-file" name="bukti_file_2" accept=".jpg" required>
                <div class="invalid-feedback">Pilih file JPG.</div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Contoh validasi form menggunakan Bootstrap
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Dapatkan semua formulir yang perlu divalidasi
                var forms = document.getElementsByClassName('needs-validation');
                // Loop melalui setiap formulir dan terapkan validasi
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
