<?php
include 'db_connection.php';

$id = $_POST['id'];
$necesitate = $_POST['necesitate'];
$username = $_POST['username'];

// Adaugă un mesaj în tabelul `mesaje` pentru a indica cine aduce necesitatea
$sql = "INSERT INTO mesaje (petrecere_id, username, text, data) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$text = "$username aduce $necesitate";
$stmt->bind_param("iss", $id, $username, $text);
$success = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $success]);
?>
