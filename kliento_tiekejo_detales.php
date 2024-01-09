<!DOCTYPE html>
<html>
<head>
    <title>Tiekėjo Informacija</title>
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

if (isset($_GET['tiekejo_id'])) {
    $tiekejo_id = $_GET['tiekejo_id'];

    $sql = "SELECT TiekejoID, Pavadinimas, Kontaktas, Adresas FROM tiekejai WHERE TiekejoID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tiekejo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Atvaizduojama tiekėjo informacija
        echo "<h2>Tiekėjo Informacija</h2>";
        echo "<p>Tiekėjo ID: " . $row['TiekejoID'] . "</p>";
        echo "<p>Pavadinimas: " . $row['Pavadinimas'] . "</p>";
        echo "<p>Kontaktas: " . $row['Kontaktas'] . "</p>";
        echo "<p>Adresas: " . $row['Adresas'] . "</p>";
    } else {
        echo "Nerasta informacijos apie tiekėją.";
    }

    $stmt->close();
} else {
    echo "Nėra nurodyto tiekėjo ID.";
}

$conn->close();
?>

</body>
</html>
