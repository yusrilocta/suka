<?php 
include '../../admin/koneksi.php';
include 'header.php';

// Fungsi untuk mendapatkan semua data calon
function getCalon($conn) {
    $query = "SELECT * FROM calon";
    $stmt = $conn->query($query);
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
                          <th>Nama</th>
                          <th>Nama Partai</th>
                          <th class="text-center">Nomer Urut</th>
                          <th></th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <?php
                      $calon = getCalon($conn);
                       foreach ($calon as $data)
                       { ?>

                       
                      <tbody>
                        <tr class="align-middle">
                          <td>
                            <div><?php echo $data['nama_calon'] ?></div>
                            
                          </td>
                          <td>
                            <div class="clearfix">
                              <div class="float-start">
                                <div class="fw-semibold"><?php echo $data['nama_partai'] ?></div>
                              </div>
                            </div>
                          </td>
                          <td class="text-center">
                          <?php echo $data['no_urut'] ?>
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-transparent p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="icon">
                                  <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-options"></use>
                                </svg>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#">Info</a><a class="dropdown-item" href="#">Edit</a><a class="dropdown-item text-danger" href="#">Delete</a></div>
                            </div>
                          </td>
                          <td> <?php
                          echo "<form method='POST' class='d-inline-block' action='input.php'>";
                    echo "<input type='hidden' name='id' value='{$data['id']}'>";
                    echo "<button type='submit' class='mx-1 btn btn-sm btn-success' name='input'>Input Data</button>";
                    echo "</form>";
                    ?>
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