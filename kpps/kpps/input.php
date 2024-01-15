<?php 

include 'header.php';
include '../koneksi.php';

$gg = $_SESSION['username'];
$sql = "SELECT * FROM tps WHERE handle = '$gg'"; 
$result = $conn->query($sql);
    while ($row = $result->fetch()) {
        $id_tps = $row['id'];
        $kec = $row['kec'];
        $desa = $row['desa'];
        $notps = $row['notps'];
}
$calon = $_POST['id'];
$sqla = "SELECT * FROM calon WHERE id = '$calon'"; 
$resulta = $conn->query($sqla);
    while ($rowa = $resulta->fetch()) {
        $id_calon = $rowa['id'];
        $nama = $rowa['nama_calon'];
        $partai = $rowa['nama_partai'];
        $nourut = $rowa['no_urut'];
}


?>
    <div class="container mt-2">
        <form action="pros_input.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            
   
            <div class="form-group">
                <label for="nama_calon">Nama Calon: </label>
                <input type="text" value="<?php echo $nama; ?>" class="form-control" name="nama_calon" disabled>
                <div class="invalid-feedback">Nama Calon harus diisi.</div>
            </div>
            <input type="text" value="<?php echo $id_calon; ?>" class="form-control" name="id_calon" >
        <input type="text" value="<?php echo $id_tps; ?>" class="form-control" name="id_tps" >

            <div class="form-group">
                <label for="partai_pengusung">Partai Pengusung:</label>
                <input type="text" value="<?php echo $partai; ?>" class="form-control" name="partai_pengusung" disabled>
                <div class="invalid-feedback">Partai Pengusung harus diisi.</div>
            </div>

            <div class="form-group">
                <label for="no_urut">No Urut:</label>
                <input type="text" value="<?php echo $nourut; ?>" class="form-control" name="no_urut" disabled>
                <div class="invalid-feedback">No Urut harus diisi.</div>
            </div>

            <div class="form-group">
                <label for="kecamatan">Kecamatan: </label>
                <input type="text" value="<?php echo $kec; ?>" class="form-control" name="kecamatan" disabled>
                <div class="invalid-feedback">Kecamatan harus diisi.</div>
            </div>


            <div class="form-group">
                <label for="desa">Desa:</label>
                <input type="text" value="<?php echo $desa; ?>" class="form-control" name="desa" disabled>
                <div class="invalid-feedback">Desa harus diisi.</div>
            </div>

            <div class="form-group">
                <label for="tps">TPS: </label>
                <input type="text" value="<?php echo $notps; ?>" class="form-control" name="tps" disabled>
                <div class="invalid-feedback">TPS harus diisi.</div>
            </div>

            <div class="form-group">
                <label for="pemilih_sah">Pemilih SAH:</label>
                <input type="text" class="form-control" name="pemilih_sah" required>
                <div class="invalid-feedback">Pemilih SAH harus diisi.</div>
            </div>

            <div class="form-group">
                <label for="pemilih_tidak_sah">Pemilih Tidak SAH:</label>
                <input type="text" class="form-control" name="pemilih_tidak_sah" required>
                <div class="invalid-feedback">Pemilih Tidak SAH harus diisi.</div>
            </div>

            <div class="form-group">
                <label for="bukti_file">Upload Bukti file jpg:</label>
                <input type="file" class="form-control-file" name="bukti_file" accept=".jpg" required>
                <div class="invalid-feedback">Pilih file JPG.</div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Tambahkan script untuk validasi form -->
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



<?php include 'footer.php'; ?>