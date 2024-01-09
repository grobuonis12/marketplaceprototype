<!DOCTYPE html>
<html>
<head>
    <title>Užbaigti užsakymai</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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

<!-- Link to tiekejo_pagrindinis -->
<a href="tiekejo_pagrindinis.php">Grįžti</a>

<h3>Užbaigti užsakymai</h3>

<table>
    <tr>
        <th>Pavadinimas</th>
        <th>Kiekis</th>
        <th>Užsakymo vertė</th>
        <th>Gautas užsakymas</th>
    </tr>
    <?php
    session_start();

    $servername = "localhost";
    $username = "ugnius";
    $password = "123";
    $dbname = "pirkimaipardavimai";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userId = $_SESSION['TiekejoPaskyrosID'];

    $sql = "SELECT gp.GautuPrekiuID, gp.KlientoID, gp.Kiekis, gp.GautuData, p.Pavadinimas, p.Kaina
            FROM gautuprekiu gp
            INNER JOIN prekes p ON gp.PrekesID = p.PrekesID
            WHERE gp.TiekejoID = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
         
            $uzsakymoVerte = $row["Kiekis"] * $row["Kaina"];

            echo "<tr>
                <td>" . $row["Pavadinimas"] . "</td>
                <td>" . $row["Kiekis"] . "</td>
                <td>" . $uzsakymoVerte . " €</td>
                <td>" . $row["GautuData"] . "</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Nėra gautų prekių</td></tr>";
    }

    $conn->close();
    ?>
</table>

</body>
</html>
