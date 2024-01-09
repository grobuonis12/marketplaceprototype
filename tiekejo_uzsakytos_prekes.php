<!DOCTYPE html>
<html>
<head>
    <title>Užsakytos Prekės</title>
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
        .order-btn {
            margin-top: 20px;
            font-size: larger;
        }
    </style>
    <a href="tiekejo_pagrindinis.php">Pagrindinis</a>
    <script>
        function openDetails(id) {
            var url = 'tiekejo_kliento_detales.php?kliento_id=' + id;
            window.open(url, '_blank', 'width=600,height=400');
        }
    </script>
</head>
<body>

<h2>Užsakytos Prekės</h2>

<table>
    <tr>
        <th>Užsakymo numeris</th>
        <th>Pavadinimas</th>
        <th>Kiekis</th>
        <th>UžsakymoData</th>
        <th>Kliento Info</th>
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

    $tiekejo_id = $_SESSION['TiekejoPaskyrosID']; 

    $sql = "SELECT u.UzsakymoID, p.Pavadinimas, u.Kiekis, u.UzsakymoData 
            FROM uzsakymai u
            INNER JOIN Prekes p ON u.PrekesID = p.PrekesID 
            WHERE p.TiekejoID = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tiekejo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["UzsakymoID"] . "</td>
                <td>" . $row["Pavadinimas"] . "</td>
                <td>" . $row["Kiekis"] . "</td>
                <td>" . $row["UzsakymoData"] . "</td>
                <td><button onclick='openDetails(" . $row["UzsakymoID"] . ")'>Daugiau</button></td>
             </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Nėra užsakytų prekių</td></tr>";
    }

    $stmt->close();
    $conn->close();
    ?>
</table>

</body>
</html>
