<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['username'] = $_POST['username'];
    header('Location: chat.php?id=' . $_POST['id']);
    exit();
}

$id = $_GET['id'];  // Preluăm ID-ul petrecerii din URL
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petrecere</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #e5ddd5; }
        #login-container { background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); width: 300px; text-align: center; }
        input[type="text"] { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px 20px; background-color: #128c7e; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div id="login-container">
        <h2>Intră în Chatul Petrecerii</h2>
        <form method="POST" action="login.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="text" name="username" placeholder="Introdu numele tău" required>
            <button type="submit">Conectează-te</button>
        </form>
    </div>
