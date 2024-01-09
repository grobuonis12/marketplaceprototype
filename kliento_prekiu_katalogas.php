<?php
session_start();

$servername = "localhost";
$username = "ugnius";
$password = "123";
$dbname = "pirkimaipardavimai";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Nepavyko prisijungti: " . $conn->connect_error);
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'PrekesID';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';
$searchQuery = ($search !== '') ? " AND Pavadinimas LIKE '%$search%'" : " AND Kiekis > 0";

// Suformuojama SQL užklausa su sąlygomis ir rikiavimu
$sql = "SELECT * FROM Prekes WHERE 1 $searchQuery ORDER BY $sort $order";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prekių Katalogas</title>
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
</head>
<body>

<a href="kliento_pagrindinis.php">Pagrindinis</a>

<h2>Prekių Katalogas</h2>

<!-- Forma ieško pagal prekių pavadinimą -->
<form method="post">
    <input type="text" name="search" placeholder="Ieškoti pagal Pavadinimą" value="<?= $search ?>">
    <input type="submit" value="Ieškoti">
    <!-- Kliento ID iš sesijos saugojamas kaip paslėptas laukas -->
    <input type='hidden' name='kliento_id' value='<?= $_SESSION['KlientoPaskyrosID']; ?>'>
    <table>
        <tr>
            <!-- Lentelės stulpelių pavadinimai su rikiavimo nuorodomis -->
            <th><a href="?sort=PrekesID&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">PrekesID</a></th>
            <th><a href="?sort=Pavadinimas&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Pavadinimas</a></th>
            <th><a href="?sort=Kategorija&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Kategorija</a></th>
            <th><a href="?sort=Kiekis&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Kiekis</a></th>
            <th><a href="?sort=Kaina&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Kaina</a></th>
            <th>Pasirinktas kiekis</th>
            <th>Tiekėjo Info</th> <!-- Naujas stulpelis -->
            <th>Užsisakyti</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <!-- Išvedami prekių duomenys -->
                    <td><?= $row["PrekesID"] ?></td>
                    <td><?= $row["Pavadinimas"] ?></td>
                    <td><?= $row["Kategorija"] ?></td>
                    <td><?= $row["Kiekis"] ?></td>
                    <td><?= $row["Kaina"] ?></td>
                    <!-- Pasirenkamo kiekio įvedimo laukas -->
                    <td>
                        <input type='number' name='selected_items[<?= $row['PrekesID'] ?>]' value='1' min='1' max='<?= $row['Kiekis'] ?>'>
                    </td>
                    <!-- Mygtukas atvaizduojantis tiekėjo informaciją -->
                    <td><button type="button" onclick="openSupplierDetails(<?= $row['TiekejoID'] ?>)">Tiekėjo Info</button></td>
                    <!-- Užsakymo checkbox'as -->
                    <td><input type='checkbox' name='selected_ids[]' value='<?= $row['PrekesID'] ?>'></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan='8'> Prekių nerasta </td></tr> <!-- Koreguotas colspan -->
        <?php endif; ?>
    </table>
    <!-- Užsakymo mygtukas su kiekių patikrinimu kliento puslapyje -->
    <input type="submit" formaction="process_order.php" value="Užsisakyti" class="order-btn" onclick="return validateQuantity()">
</form>

<!-- Skriptas, kuris atidaro tiekėjo informacijos langą -->
<script>
    function openSupplierDetails(id) {
        var url = 'kliento_tiekejo_detales.php?tiekejo_id=' + id;
        window.open(url, '_blank', 'width=600,height=400');
    }
</script>

<!-- Skriptas tikrinantis užsakymo kiekius ir checkbox'ų būseną -->
<script>
    function validateQuantity() {
        var selectedCheckboxes = document.querySelectorAll('input[name="selected_ids[]"]');
        var atLeastOneSelected = false;

        // Tikrinama, ar bent vienas checkbox'as pažymėtas
        for (var i = 0; i < selectedCheckboxes.length; i++) {
            if (selectedCheckboxes[i].checked) {
                atLeastOneSelected = true;
                break;
            }
        }

        // Jei nėra pažymėtų checkbox'ų, rodomas pranešimas
        if (!atLeastOneSelected) {
            alert("Pasirinkite prekę");
            return false;
        }

        var selectedQuantities = document.querySelectorAll('input[name^="selected_items"]');

        // Tikrinama, ar pasirinktas kiekis nedidesnis nei turimas kiekis
        for (var i = 0; i < selectedQuantities.length; i++) {
            var quantity = parseInt(selectedQuantities[i].value);
            var maxQuantity = parseInt(selectedQuantities[i].max);

            if (quantity > maxQuantity && selectedCheckboxes[i].checked) {
                alert("Pasirinktas kiekis negali būti didesnis nei turimas kiekis.");
                return false;
            }
        }

        return true;
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
