<?php 
include '../koneksi.php';
session_start();
    $id_calon = $_POST['id_calon'];
    $id_tps = $_POST['id_tps'];
    $pemilih_sah = $_POST['pemilih_sah'];
    $pemilih_tidak_sah = $_POST['pemilih_tidak_sah'];
    $total = $pemilih_sah + $pemilih_tidak_sah;
    $handle = $_SESSION['username'];

    // Upload file
    $target_directory = "uploads/"; // direktori untuk menyimpan file
    $target_file = $target_directory . basename($_FILES["bukti_file"]["name"]);
    move_uploaded_file($_FILES["bukti_file"]["tmp_name"], $target_file);

$sql = "INSERT INTO pemilu (id_tps, id_calon, peml, pemp,total, bukti_file, handle) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(1, $id_tps);
$stmt->bindParam(2, $id_calon);
$stmt->bindParam(3, $pemilih_sah);
$stmt->bindParam(4, $pemilih_tidak_sah);
$stmt->bindParam(5, $total);
$stmt->bindParam(6, $target_file);
$stmt->bindParam(7, $handle);

    if ($stmt->execute()) {
        header("Location: pemilu.php");
    }

$data = array(
                'id_calon' => $_POST['id_calon'],
                'id_tps' => $_POST['id_tps'],
                'peml' => $_POST['peml'],
                'pemp' => $_POST['pemp'],
            );
        
            // Query untuk meng-input data array ke dalam tabel "pemilu"
            $query = "INSERT INTO pemilu (id_calon,id_tps, peml, pemp,total) VALUES (?, ?, ?, ?,?)";
            $stmt = $conn->prepare($query);
        
            // Binding parameter ke statement
            $stmt->bind_param('sssss', $id_calon, $id_tps, $peml, $pemp,$total);
        
            // Looping untuk menginput data dari array
            foreach ($data['id_calon'] as $key => $value) {
                $id_calon = $value;
                $id_tps = $data['id_tps'][$key];
                $peml = $data['peml'][$key];
                $pemp = $data['pemp'][$key];
                $total = $peml + $pemp;
        
                // Eksekusi query untuk setiap data
                if ($stmt->execute()) {
                    echo "Data berhasil di-input ke database.";
                    
                } else {
                    echo "Terjadi kesalahan: " . $stmt->error;
                    break; 
                }
            }

?>