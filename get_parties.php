<?php
include 'db_connection.php';

$sql = "SELECT * FROM petreceri";
$result = $conn->query($sql);

$petreceri = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $petreceri[] = $row;
    }
}

echo json_encode($petreceri);
$conn->close();
?>
