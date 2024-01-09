<?php
session_start();

if (!isset($_SESSION['KlientoPaskyrosID'])) {
    header("Location: kliento_prisijungimas.php");
    exit();
}

$servername = "localhost";
$username = "ugnius";
$password = "123";
$dbname = "pirkimaipardavimai";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Nepavyko prisijungti: " . $conn->connect_error);
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'UzsakymoID';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';

$searchQuery = '';
if ($search !== '') {
    if (is_numeric($search)) {
        $searchQuery = " AND PrekesID = $search";
    }
}

$userId = $_SESSION['KlientoPaskyrosID'];

$sql = "SELECT * FROM uzsakymai WHERE KlientoID = '$userId' $searchQuery ORDER BY $sort $order";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jūsų Užsakymai</title>
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
    <script>
        function validateForm() {
            var checkboxes = document.getElementsByName('gauta[]');
            var quantities = document.getElementsByName('enteredQuantity[]');

            var isChecked = false;
            var isQuantitySet = true;

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    isChecked = true;
                    if (quantities[i] && quantities[i].value === '') {
                        isQuantitySet = false;
                        break;
                    }
                }
            }

            if (!isChecked) {
                alert("Nepažymėjote prekės");
                return false;
            }

            if (!isQuantitySet) {
                alert("Kiekis gautų prekių neįrašytas");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <a href="kliento_pagrindinis.php">Pagrindinis</a>
    <h3>Jūsų Užsakymai</h3>

    <form method="post" action="patvirtinti_kiekius.php" onsubmit="return validateForm()">
        <table>
            <tr>
                <th>Užsakymo numeris</th>
                <th>Prekės Pavadinimas</th>
                <th>Kiekis</th>
                <th>Kaina</th> 
                <th>Užsakymo Data</th>
                <th>Gauta?</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $itemId = $row['PrekesID'];

                    $prekesQuery = "SELECT Pavadinimas FROM prekes WHERE PrekesID = '$itemId'";
                    $prekesResult = $conn->query($prekesQuery);
                    $prekesRow = $prekesResult->fetch_assoc();
                    $pavadinimas = $prekesRow['Pavadinimas'];

                    echo "<tr>
                        <td>" . $row["UzsakymoID"] . "</td>
                        <td>" . $pavadinimas . "</td>
                        <td>" . $row["Kiekis"] . "</td>
                        <td>" . $row["Kaina"] * $row["Kiekis"] . "</td>
                        <td>" . $row["UzsakymoData"] . "</td>
                        <td>
                            <input type='checkbox' name='gauta[]' value='" . $row['UzsakymoID'] . "'>
                            <input type='hidden' name='supplierId[]' value='" . $row['TiekejoID'] . "'>
                            <input type='hidden' name='itemId[]' value='" . $row['UzsakymoID'] . "'>
                        </td>
                     </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Užsakymų nerasta</td></tr>";
            }
            ?>
        </table>
        <input type="submit" value="Patvirtinti" class="order-btn">
    </form>

    <?php
    $conn->close();
    ?>
</body>
</html>
