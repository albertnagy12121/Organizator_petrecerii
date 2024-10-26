<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = $_POST['nume'];
    $data = $_POST['data'];
    $locatie = $_POST['locatie'];
    $parola = $_POST['parola'];
    $buget = $_POST['buget'];
    $necesitati = $_POST['necesitati'];
    $sql = "INSERT INTO petreceri (nume, data, locatie,buget,necesitati,parola) VALUES ('$nume', '$data', '$locatie', '$buget','$necesitati','$parola')";

    if ($conn->query($sql) === TRUE) {
        echo "Petrecere adaugata cu succes";
    } else {
        echo "Eroare: " . $conn->error;
    }
}

$conn->close();
?>
