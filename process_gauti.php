<?php
session_start();

if (!isset($_SESSION['KlientoPaskyrosID'])) {
    header("Location: kliento_prisijungimas.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enteredQuantity']) && isset($_POST['selectedItems'])) {
    $enteredQuantities = $_POST['enteredQuantity'];
    $selectedItems = $_POST['selectedItems'];

    $servername = "localhost";
    $username = "ugnius";
    $password = "123";
    $dbname = "pirkimaipardavimai";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Prisijungimas nepavyko: " . $conn->connect_error);
    }

    foreach ($selectedItems as $itemId) {
        $userId = $_SESSION['KlientoPaskyrosID'];

        $enteredQuantity = (int)$enteredQuantities[$itemId];

        $quantityQuery = "SELECT Kiekis, PrekesID FROM uzsakymai WHERE UzsakymoID = '$itemId'";
        $quantityResult = $conn->query($quantityQuery);

        if ($quantityResult->num_rows > 0) {
            $row = $quantityResult->fetch_assoc();
            $dbQuantity = (int)$row['Kiekis'];
            $prekesId = $row['PrekesID'];

            if ($enteredQuantity === $dbQuantity) {
                $query = "INSERT INTO gautuprekiu (PrekesID, Kiekis, GautuData, KlientoID, TiekejoID)
                          SELECT PrekesID, Kiekis, NOW(), '$userId', TiekejoID 
                          FROM uzsakymai 
                          WHERE UzsakymoID = '$itemId'";

                if ($conn->query($query) === TRUE) {
                    $deleteQuery = "DELETE FROM uzsakymai WHERE UzsakymoID = '$itemId'";
                    if ($conn->query($deleteQuery) === TRUE) {
                        header("Location: klientu_uzsakymai.php");
                        exit();
                    } else {
                        error_log("Klaida pašalinant užsakymą su ID: $itemId - " . $conn->error);
                    }
                } else {
                    error_log("Klaida perkeldant užsakymą su ID: $itemId - " . $conn->error);
                }
            } else {
                // Kodas jeigu neatitinka užsakymo kiekis su įvestu
                $supplierQuery = "SELECT tiekejai.* FROM prekes 
                                  INNER JOIN tiekejai ON prekes.TiekejoID = tiekejai.TiekejoID 
                                  WHERE prekes.PrekesID = '$prekesId'";
                $supplierResult = $conn->query($supplierQuery);

                if ($supplierResult->num_rows > 0) {
                    $supplierInfo = $supplierResult->fetch_assoc();

                    $supplierDetails = "<h1>Neatitinka kiekis, kreipkitės į Tiekėją</h1>";
                    $supplierDetails .= "<p>TiekejoID: " . $supplierInfo['TiekejoID'] . "</p>";
                    $supplierDetails .= "<p>Pavadinimas: " . $supplierInfo['Pavadinimas'] . "</p>";
                    $supplierDetails .= "<p>Adresas: " . $supplierInfo['Adresas'] . "</p>";


                    $_SESSION['supplierDetails'] = $supplierDetails;

                    header("Location: uzsakytos_prekes_tiekejas.php");
                } else {
                    echo "Tiekėjo informacija nerasta.";
                }
            }
        }
    }

    $conn->close();
} else {
    header("Location: kliento_pagrindinis.php");
    exit();
}
?>
