<?php
session_start();

if (!isset($_SESSION['KlientoPaskyrosID'])) {
    header("Location: kliento_prisijungimas.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['gauta'])) {
    header("Location: kliento_pagrindinis.php");
    exit();
}

$selectedItems = $_POST['gauta'];

$servername = "localhost";
$username = "ugnius";
$password = "123";
$dbname = "pirkimaipardavimai";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Nepavyko prisijungti: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patvirtinti Kiekius</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin-bottom: 20px;
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
            font-size: larger;
        }
    </style>
</head>
<body>
    <h3>Patvirtinti Kiekius</h3>

    <form method="post" action="process_gauti.php">
        <table>
            <tr>
                <th>Užsakymo ID</th>
                <th>Prekės ID</th>
                <th>Užsakytas kiekis</th>
                <th>Įvesti gautą kiekį</th>
            </tr>
            <?php
            foreach ($selectedItems as $itemId) {
                $sql = "SELECT * FROM uzsakymai WHERE UzsakymoID = '$itemId'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo "<tr>
                            <td>" . $row["UzsakymoID"] . "</td>
                            <td>" . $row["PrekesID"] . "</td>
                            <td>" . $row["Kiekis"] . "</td>
                            <td>
                                <input type='number' name='enteredQuantity[" . $row['UzsakymoID'] . "]' placeholder='Įveskite kiekį' required>
                                <input type='hidden' name='selectedItems[]' value='" . $row['UzsakymoID'] . "'>
                            </td>
                        </tr>";
                }
            }
            ?>
        </table>
        <input type="submit" value="Patvirtinti Kiekius" class="order-btn">
    </form>

    <?php
    $conn->close();
    ?>
</body>
</html>
