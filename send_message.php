<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];  // ID-ul petrecerii
    $username = $_POST['username'];  // Numele utilizatorului
    $message = $_POST['message'];

    $sql = "INSERT INTO mesaje (petrecere_id, username, text) VALUES ('$id', '$username', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Mesaj trimis";
    } else {
        echo "Eroare: " . $conn->error;
    }
}

$conn->close();
?>
