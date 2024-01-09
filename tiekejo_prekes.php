<?php
session_start();

$servername = "localhost";
$username = "ugnius";
$password = "123";
$dbname = "pirkimaipardavimai";

$conn = new mysqli($servername, $username, $password, $dbname);

// Tikriname duomenų bazės prisijungimą
if ($conn->connect_error) {
    die("Nepavyko prisijungti: " . $conn->connect_error);
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'PrekesID';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$search = isset($_POST['search']) ? $_POST['search'] : '';

if ($search !== '') {
    $search = $conn->real_escape_string($search);
    if (is_numeric($search)) {
        $searchQuery = " AND PrekesID = $search";
    } else {
        $searchQuery = '';
    }
} else {
    $searchQuery = '';
}

$tiekejo_id = $_SESSION['TiekejoPaskyrosID']; // Gauti prisijungusio tiekėjo TiekėjoID
$sql = "SELECT * FROM Prekes WHERE TiekejoID = $tiekejo_id $searchQuery ORDER BY $sort $order";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <title>Jūsų Prekių Katalogas</title>
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

<!-- Nuoroda į tiekejo_pagrindinis.php -->
<a href="tiekejo_pagrindinis.php">Pagrindinis</a>

<h2>Jūsų Prekių Katalogas</h2>

<!-- Forma, leidžianti ieškoti prekių pagal Prekės ID -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="search" placeholder="Ieškoti pagal Prekės ID" value="<?= $search ?>">
    <input type="submit" value="Ieškoti">
    <input type='hidden' name='kliento_id' value='<?= $_SESSION['TiekejoPaskyrosID']; ?>'>
    <table>
        <tr>
            <!-- Lentelės stulpelių antraštės su nuorodomis rūšiavimui -->
            <th><a href="?sort=PrekesID&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">PrekėsID</a></th>
            <th><a href="?sort=Pavadinimas&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Pavadinimas</a></th>
            <th><a href="?sort=Kategorija&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Kategorija</a></th>
            <th><a href="?sort=Kiekis&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Kiekis</a></th>
            <th><a href="?sort=Kaina&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Kaina</a></th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Atvaizduojame prekių sąrašą lentelėje
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["PrekesID"] . "</td>
                    <td>" . $row["Pavadinimas"] . "</td>
                    <td>" . $row["Kategorija"] . "</td>
                    <td>" . $row["Kiekis"] . "</td>
                    <td>" . $row["Kaina"] . "</td>
                 </tr>";
            }
        } else {
            echo "<tr><td colspan='5'> Prekių nerasta </td></tr>";
        }
        ?>
    </table>
</form>

</body>
</html>

<?php
$conn->close();
?>
