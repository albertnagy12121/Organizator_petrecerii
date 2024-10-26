<?php
include 'db_connection.php';

$id = $_GET['id'];
$parola = $_GET['parola'];

$sql = "SELECT parola FROM petreceri WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['parola'] === $parola) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
} else {
    echo json_encode(["success" => false]);
}

$conn->close();
?>
