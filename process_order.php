<?php
session_start();

// Patikrinama, ar yra vartotojo sesija
if (!isset($_SESSION['KlientoPaskyrosID'])) {
    header("Location: kliento_prisijungimas.php");
    exit();
}

// Tikrinama, ar yra POST užklausa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tikrinama, ar gauti pasirinkti elementai ir kiekiai
    if (isset($_POST['selected_ids']) && isset($_POST['selected_items'])) {
        $selectedItems = $_POST['selected_ids']; // Gaunami pasirinkti elementai
        $selectedQuantities = $_POST['selected_items']; // Gaunami pasirinkti kiekiai

        // Duomenų bazės prisijungimo duomenys
        $servername = "localhost";
        $username = "ugnius";
        $password = "123";
        $dbname = "pirkimaipardavimai";

        // Prisijungiama prie duomenų bazės
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Tikrinama, ar prisijungimas pavyko
        if ($conn->connect_error) {
            die("Prisijungimas nepavyko: " . $conn->connect_error);
        }

        // Iteruojama per pasirinktus elementus
        foreach ($selectedItems as $itemId) {
            $quantity = $selectedQuantities[$itemId]; // Gaunamas kiekis pagal pasirinktą elementą

            $orderDate = date("Y-m-d"); 

            // Gaunami TiekejoID ir Kaina iš prekes lentelės pagal duotą PrekesID
            $query = "SELECT TiekejoID, Kaina FROM prekes WHERE PrekesID = '$itemId'";
            $result = $conn->query($query);

            // Tikrinama, ar duomenys rasti
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $tiekejoID = $row['TiekejoID']; // Gaunamas TiekejoID
                $kaina = $row['Kaina']; // Gaunama Kaina

                // Skaičiuojama bendra kaina pagal kiekį ir Kainą
                $totalPrice = $kaina * $quantity;

                // Įterpiama į 'uzsakymai' lentelę su gautu TiekejoID ir apskaičiuota bendra kaina
                $insertQuery = "INSERT INTO uzsakymai (PrekesID, KlientoID, Kiekis, UzsakymoData, TiekejoID, Kaina) 
                                VALUES ('$itemId', '{$_SESSION['KlientoPaskyrosID']}', '$quantity', '$orderDate', '$tiekejoID', '$totalPrice')";
                $conn->query($insertQuery);

                // Atnaujinamas prekių kiekis 'prekes' lentelėje
                $updateQuery = "UPDATE prekes SET Kiekis = Kiekis - $quantity WHERE PrekesID = '$itemId'";
                $conn->query($updateQuery);
            } else {
                echo "Nerasta atitinkamų duomenų PrekesID: $itemId";
            }
        }

        $conn->close(); // Uždaromas ryšys su duomenų baze

        header("Location: klientu_uzsakymai.php");
        exit();
    }
}
?>
