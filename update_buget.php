<?php
include 'db_connection.php';

$id = $_POST['id'];
$necesitate = $_POST['necesitate'];
$cost = $_POST['cost'];
$username = $_POST['username'];

// Obține informațiile curente despre buget și necesități
$sql = "SELECT buget, necesitati, cost FROM petreceri WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($buget, $necesitati, $costuri);
$stmt->fetch();
$stmt->close();

// Transformă necesitățile și costurile în array-uri
$necesitati_array = explode(',', $necesitati);
$costuri_array = explode(',', $costuri);

// Caută indexul necesității
$index = array_search($necesitate, $necesitati_array);
if ($index === false) {
    echo json_encode(['success' => false, 'message' => 'Necessitate not found']);
    exit();
}

// Actualizează costul la indexul necesității
$costuri_array[$index] = $cost;

// Salvează noile valori în șiruri separate prin virgulă
$costuri_noi = implode(',', $costuri_array);

// Scade costul din buget
$buget_actualizat = $buget - $cost;

// Actualizează baza de date
$sql = "UPDATE petreceri SET buget = ?, cost = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("dsi", $buget_actualizat, $costuri_noi, $id);
$success = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $success]);
?>
