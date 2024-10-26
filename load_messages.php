<?php
include 'db_connection.php';

$id = $_GET['id'];

// Selectează mesajele pentru petrecerea specificată
$sql = "SELECT username, text, data FROM mesaje WHERE petrecere_id = ? ORDER BY data ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'username' => $row['username'],
        'text' => $row['text'],
        'data' => $row['data']
    ];
}

$stmt->close();
$conn->close();

echo json_encode($messages);
?>
