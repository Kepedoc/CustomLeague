<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    $data = htmlspecialchars($_POST['data']);
    $conn = new mysqli('localhost', 'root', '', 'loc');
    echo "Otrzymano dane: " . $data;
    if ($conn->connect_error) {
        die('Błąd połączenia: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO user (NICK, WIN) VALUES ($data, 0);
    $stmt->bind_param('s', $data);

    if ($stmt->execute()) {
        echo 'Dane zapisane!';
    } else {
        echo 'Błąd zapisu!';
    }

    $stmt->close();
    $conn->close();
}
?>