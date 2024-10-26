<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    $id = $_GET['id'];
    header("Location: login.php?id=$id");
    exit();
}

$username = $_SESSION['username'];
$id = $_GET['id'];

// Obține necesitățile din tabelul `petreceri`
$sql = "SELECT necesitati FROM petreceri WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($necesitati);
$stmt->fetch();
$stmt->close();

$necesitati_array = explode(',', $necesitati);

// Obține mesajele care indică responsabilitățile din tabelul `mesaje`
$responsabilitati = [];
$sql = "SELECT text FROM mesaje WHERE petrecere_id = ? AND text LIKE '%aduce%'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $responsabilitati[] = $row['text'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Petrecere</title>
    <style>
        body { background-color: #e5ddd5; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        #chat-container { width: 90%; max-width: 400px; background-color: #ffffff; padding: 10px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); }
        
        #necesitati-container { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 10px; }
        
        .necesitate {
            background-color: #25d366;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .necesitate.selectat {
            background-color: #ff4c4c; /* Roșu când este selectat */
        }
        
        .responsabil {
            font-size: 12px;
            color: #555;
        }

        #chat-messages { max-height: 300px; overflow-y: auto; margin-bottom: 10px; }
        .message { background-color: #dcf8c6; padding: 10px; border-radius: 8px; margin: 5px 0; }
        
        .input-container { display: flex; }
        input[type="text"] { flex: 1; padding: 8px; }
        button { padding: 8px 10px; background-color: #128c7e; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div id="chat-container">
        <h2>Chat Petrecere</h2>
        
        <!-- Container pentru butoanele de necesități -->
        <div id="necesitati-container">
            <?php foreach ($necesitati_array as $index => $necesitate): ?>
                <?php
                // Verifică dacă există un responsabil pentru necesitatea curentă
                $responsabil_text = "";
                $selectat = "";
                foreach ($responsabilitati as $responsabilitate) {
                    if (strpos($responsabilitate, "aduce $necesitate") !== false) {
                        $responsabil_text = $responsabilitate;
                        $selectat = "selectat";
                        break;
                    }
                }
                ?>
                <button 
                    class="necesitate <?php echo $selectat; ?>" 
                    onclick="handleNecessityClick('<?php echo $necesitate; ?>', <?php echo $index; ?>)">
                    <?php echo htmlspecialchars($necesitate); ?>
                </button>
                <div class="responsabil" id="responsabil-<?php echo $index; ?>">
                    <?php echo htmlspecialchars($responsabil_text); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Container pentru mesaje de chat -->
        <div id="chat-messages">
            <!-- Mesajele vor apărea aici -->
        </div>
        
        <!-- Input pentru trimiterea de mesaje -->
        <div class="input-container">
            <input type="text" id="message" placeholder="Scrie un mesaj...">
            <button onclick="sendMessage()">Trimite</button>
        </div>
    </div>

    <script>
        const partyId = <?php echo $id; ?>;
        const username = "<?php echo $username; ?>";

        function handleNecessityClick(necesitate, index) {
            fetch('update_responsabil.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${partyId}&necesitate=${encodeURIComponent(necesitate)}&username=${encodeURIComponent(username)}`
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const button = document.querySelector(`.necesitate:nth-child(${index * 2 + 1})`);
                      button.classList.add('selectat');
                      document.getElementById(`responsabil-${index}`).innerText = `${username} aduce ${necesitate}`;
                  } else {
                      alert("Eroare la actualizarea responsabilului!");
                  }
              });
        }

        // Funcția pentru încărcarea mesajelor de chat
        function loadMessages() {
            fetch(`load_messages.php?id=${partyId}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('chat-messages');
                    container.innerHTML = '';
                    data.forEach(msg => {
                        const div = document.createElement('div');
                        div.className = 'message';
                        div.innerText = `${msg.username}: ${msg.text} \n ${new Date(msg.data).toLocaleString()}`;
                        container.appendChild(div);
                    });
                    container.scrollTop = container.scrollHeight;  // Scroll automat la ultimul mesaj
                });
        }

      
        function sendMessage() {
            const message = document.getElementById('message').value;
            fetch('send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${partyId}&username=${encodeURIComponent(username)}&message=${encodeURIComponent(message)}`
            }).then(response => {
                document.getElementById('message').value = '';
                loadMessages();
            });
        }

        setInterval(loadMessages, 3000);  // Reîmprospătare mesaje la fiecare 3 secunde
        loadMessages();
    </script>
</body>
</html>
