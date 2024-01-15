<?php 
include '../../admin/koneksi.php';
include 'header.php';
// Fungsi untuk mendapatkan semua data calon
function getCalon($conn) {

    $gg = $_SESSION['username'];
    $query = "SELECT * FROM tps where handle = :handle";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':handle', $gg);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="container">
<div class="row">
    <div class="col">
          <div class="table-responsive bg-white">
                    <table class="table border mb-0">
                      <thead class=" fw-semibold">
                        <tr class="align-middle">
                          <th>Kecamatan</th>
                          <th>Desa</th>
                          <th class="text-center">TPS</th>
                          <th class="text-center">laki-Laki</th>
                          <th class="text-center">Perempuan</th>
                          <th class="text-center">Suara Cadangan</th>
                          <th>Total DPT</th>
                          <th>Suara rusak</th>
            <th>dptb</th>
            
            <th>dpk</th>
            <th>suara diterima</th>
            <th>suara digunakan</th>
            <th>suara Rusak</th>
            <th>tidak digunakan</th>
                          <th></th>
                        </tr>
                      </thead>
                      <?php
                      $calon = getCalon($conn);
                       foreach ($calon as $data)

                       { ?>

                       
                      <tbody>
                        <tr class="align-middle">
                          <td>
                            <div><?php echo $data['kec'] ?></div>
                          </td>
                          <td>
                            <div class="clearfix">
                              <div class="float-start">
                                <div class="fw-semibold"><?php echo $data['desa'] ?></div>
                              </div>
                            </div>
                          </td>
                          <td class="text-center">
                          <?php echo $data['notps'] ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['peml'] ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['pemp']; ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['duaper']; ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['dpt'] ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['sisa_suara'] ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['dptb'] ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['dpk'] ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['suara_diterima'] ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['suara_digunakan'] ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['suara_rusak'] ?>
                          </td>
                          <td class="text-center">
                          <?php echo $data['suara_tak_terguna'] ?>
                          </td>

                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                  </div>
        
                  </div>
</div>

<?php include 'footer.php'; ?>