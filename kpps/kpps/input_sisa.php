<?php 

include 'header.php';
include '../koneksi.php';

$gg = $_SESSION['username'];
$sql = "SELECT * FROM tps WHERE handle = 'sui'"; 
$result = $conn->query($sql);
    while ($row = $result->fetch()) {
        $id_tps = $row['id'];
        $kec = $row['kec'];
        $desa = $row['desa'];
        $notps = $row['notps'];
}



?>
    <div class="container mt-2">
        <form action="pros_input_sisa.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
          
            <input type="text" value="<?php echo $id_tps; ?>" class="form-control" name="id_tps" >

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
                <label for="sisa_suara">Sisa Suara :</label>
                <input type="text" class="form-control" name="sisa_suara" required>
                <div class="invalid-feedback">Sisa Suara harus diisi.</div>
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