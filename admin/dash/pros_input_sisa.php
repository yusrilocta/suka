<?php 
include '../koneksi.php';
session_start();

$id = $_POST['id_tps'];
$gg = $_POST['handle'];
$sisa_suara = $_POST['sisa_suara'];
$dpt = $_POST['dpt'];
$dptb = $_POST['dptb'];
$dpk = $_POST['dpk'];
$suara_diterima = $_POST['suara_diterima'];
$suara_digunakan = $_POST['suara_digunakan'];
$suara_rusak = $_POST['suara_rusak'];
$suara_tak_terguna = $_POST['suara_tak_terguna'];

    // Upload file
    $target_directory = "uploads/"; // direktori untuk menyimpan file
    $target_file = $target_directory . basename($_FILES["bukti_file"]["name"]);
    move_uploaded_file($_FILES["bukti_file"]["tmp_name"], $target_file);

        $target_file_2 = $target_directory . basename($_FILES["bukti_file_2"]["name"]);
        move_uploaded_file($_FILES["bukti_file_2"]["tmp_name"], $target_file_2);


$sql = "UPDATE tps SET sisa_suara = ?, dpt = ?, dptb = ?, dpk = ?, suara_diterima = ?, suara_digunakan = ?, suara_rusak = ?, suara_tak_terguna = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

$stmt->bindParam(1, $sisa_suara, PDO::PARAM_INT);
$stmt->bindParam(2, $dpt, PDO::PARAM_INT);
$stmt->bindParam(3, $dptb, PDO::PARAM_INT);
$stmt->bindParam(4, $dpk, PDO::PARAM_INT);
$stmt->bindParam(5, $suara_diterima, PDO::PARAM_INT);
$stmt->bindParam(6, $suara_digunakan, PDO::PARAM_INT);
$stmt->bindParam(7, $suara_rusak, PDO::PARAM_INT);
$stmt->bindParam(8, $suara_tak_terguna, PDO::PARAM_INT);
$stmt->bindParam(9, $id, PDO::PARAM_INT);
$stmt->execute();

$data = array(
    'id_calon' => $_POST['id_calon'],

    'peml' => $_POST['peml'],
);
$id_tps = $_POST['id_tps'];

$query = "INSERT INTO pemilu (id_calon, id_tps, peml, handle, bukti_file, bukti_file_2 ) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

foreach ($data['id_calon'] as $key => $value) {
    $id_calon = $value;
    $id_tps = $id_tps;
    $peml = $data['peml'][$key];

    $stmt->bindValue(1, $id_calon);
    $stmt->bindValue(2, $id_tps);
    $stmt->bindValue(3, $peml);
    $stmt->bindValue(4, $gg);
    $stmt->bindValue(5, $target_file);
    $stmt->bindValue(6, $target_file_2);

    // Eksekusi pernyataan INSERT
    $stmt->execute();
}

// Redirect setelah semua data diinput
header("Location: tps.php");

?>
