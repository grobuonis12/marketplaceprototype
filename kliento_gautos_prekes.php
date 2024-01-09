<?php
session_start(); // Paleidžiama sesija

$servername = "localhost";
$username = "ugnius";
$password = "123";
$dbname = "pirkimaipardavimai";

$conn = new mysqli($servername, $username, $password, $dbname); // Prisijungiama prie duomenų bazės

if ($conn->connect_error) { // Tikrinama duomenų bazės prisijungimo klaida
    die("Connection failed: " . $conn->connect_error); // Jei prisijungimas nepavyksta, išvedamas pranešimas ir sustabdomas kodas
}

$klientoPaskyrosId = $_SESSION['KlientoPaskyrosID']; // Gaunamas kliento ID iš sesijos

$sql = "SELECT g.GautuPrekiuID, p.Pavadinimas, g.Kiekis, g.GautuData, p.Kaina
        FROM gautuprekiu g
        INNER JOIN prekes p ON g.PrekesID = p.PrekesID
        WHERE g.KlientoID = '$klientoPaskyrosId'";
$result = $conn->query($sql); // Vykdoma SQL užklausa

?>

<!DOCTYPE html>
<html>
<head>
    <title>Užbaigti užsakymai</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Nuoroda į kliento_pagrindinis.php -->
    <a href="kliento_pagrindinis.php">Pagrindinis</a>

    <h3>Užbaigti užsakymai</h3>

    <table>
        <tr>
            <th>Pavadinimas</th>
            <th>Kiekis</th>
            <th>Gautas užsakymas</th>
            <th>Užsakymo vertė</th>
        </tr>
        <?php
        if ($result->num_rows > 0) { // Tikrinama, ar yra rezultatų
            while ($row = $result->fetch_assoc()) { // Atvaizduojami rezultatai
                // Perform the calculation here for "Užsakymo vertė"
                $uzsakymoVerte = $row["Kiekis"] * $row["Kaina"];

                echo "<tr>
                    <td>" . $row["Pavadinimas"] . "</td>
                    <td>" . $row["Kiekis"] . "</td>
                    <td>" . $row["GautuData"] . "</td>
                    <td>" . $uzsakymoVerte . " €</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nėra gautų prekių</td></tr>"; // Jei nėra rezultatų, rodomas pranešimas
        }
        ?>
    </table>

    <?php
    $conn->close(); // Uždaromas ryšys su duomenų baze
    ?>
</body>
</html>
