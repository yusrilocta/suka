<?php 
include '../koneksi.php';
session_start();

$id_tps = $_POST['id_tps'];
$sisa_suara = $_POST['sisa_suara'];
$dpt = $_POST['dpt'];
$dptb = $_POST['dptb'];
$dpk = $_POST['dpk'];
$suara_diterima = $_POST['suara_diterima'];
$suara_digunakan = $_POST['suara_digunakan'];
$suara_rusak = $_POST['suara_rusak'];
$suara_tak_terguna = $_POST['suara_tak_terguna'];

// UPDATE tps
$sql = "UPDATE tps 
        SET sisa_suara = ?, dpt = ?, dptb = ?, dpk = ?, suara_diterima = ?, suara_digunakan = ?, suara_rusak = ?, suara_tak_terguna = ? 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bindParam(1, $sisa_suara, PDO::PARAM_INT);
$stmt->bindParam(2, $dpt, PDO::PARAM_INT);
$stmt->bindParam(3, $dptb, PDO::PARAM_INT);
$stmt->bindParam(4, $dpk, PDO::PARAM_INT);
$stmt->bindParam(5, $suara_diterima, PDO::PARAM_INT);
$stmt->bindParam(6, $suara_digunakan, PDO::PARAM_INT);
$stmt->bindParam(7, $suara_rusak, PDO::PARAM_INT);
$stmt->bindParam(8, $suara_tak_terguna, PDO::PARAM_INT);
$stmt->bindParam(9, $id_tps, PDO::PARAM_INT);
$stmt->execute();

// UPDATE pemilu
$data = array(
    'id_calon' => $_POST['id_calon'],
    'id_tps' => $_POST['id_tps'],
    'peml' => $_POST['peml'],
);
$query = "UPDATE pemilu 
          SET peml = ?
          WHERE id_tps = ? AND id_calon = ?";
$stmt = $conn->prepare($query);


foreach ($data['id_calon'] as $key => $value) {
    $id_calon = $value;
    $peml = $data['peml'][$key];
    $id_tps = $data['id_tps'];
    $stmt->bindValue(1, $peml, PDO::PARAM_INT);
    $stmt->bindValue(2, $id_tps, PDO::PARAM_INT);
    $stmt->bindValue(3, $id_calon, PDO::PARAM_INT);
    $stmt->execute();
}

?>
