<!DOCTYPE html>
<html>
<head>
    <title>Kliento Informacija</title>
</head>
<body>

<?php
session_start();

$servername = "localhost";
$username = "ugnius";
$password = "123";
$dbname = "pirkimaipardavimai";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Prisijungimas nepavyko: " . $conn->connect_error);
}

if (isset($_GET['kliento_id'])) {
    $kliento_id = $_GET['kliento_id'];

    $sql = "SELECT KlientoID, Vardas, Pavarde, Kontaktas, Adresas FROM klientai WHERE KlientoID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kliento_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Atvaizduojama kliento informacija
        echo "<h2>Kliento Informacija</h2>";
        echo "<p>Kliento ID: " . $row['KlientoID'] . "</p>";
        echo "<p>Vardas: " . $row['Vardas'] . "</p>";
        echo "<p>Pavardė: " . $row['Pavarde'] . "</p>";
        echo "<p>Kontaktas: " . $row['Kontaktas'] . "</p>";
        echo "<p>Adresas: " . $row['Adresas'] . "</p>";
    } else {
        echo "Nerasta informacijos apie klientą.";
    }

    $stmt->close();
} else {
    echo "Nėra nurodyto kliento ID.";
}

$conn->close();
?>


</body>
</html>
