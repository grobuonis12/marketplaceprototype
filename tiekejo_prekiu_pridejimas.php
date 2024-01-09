<!DOCTYPE html>
<html>
<head>
    <title>Pridėti produktą</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Pridėti produktą</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
        <label for="pavadinimas">Pavadinimas:</label><br>
        <input type="text" id="pavadinimas" name="pavadinimas"><br>
        
        
        <label for="kategorija">Kategorija(Skaičius):</label><br>
        <input type="text" id="kategorija" name="kategorija"><br>
        
        
        <label for="kiekis">Kiekis(Skaičius):</label><br>
        <input type="text" id="kiekis" name="kiekis"><br>
        
       
        <label for="kaina">Kaina(Skaičius) (+10% prie kainos tarpininko mokestis):</label><br>
        <input type="text" id="kaina" name="kaina"><br><br>
        
        <input type="submit" value="Submit">
    </form>

    <?php
    session_start();

    // Duomenų bazės prisijungimo konfigūracija
    $servername = "localhost";
    $username = "ugnius";
    $password = "123";
    $dbname = "pirkimaipardavimai";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Tikriname duomenų bazės prisijungimą
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Gauti produktų informaciją iš formos
        $pavadinimas = $_POST['pavadinimas'];
        $kategorija = $_POST['kategorija'];
        $kiekis = $_POST['kiekis'];
        $kaina = $_POST['kaina'];

        // Patikrinimas ar visi laukai užpildyti
        if (empty($pavadinimas) || empty($kategorija) || empty($kiekis) || empty($kaina)) {
            echo "Ne visi laukai užpildyti";
        } else {
            // Patikrinimas ar "Kiekis", "Kategorija", ir "Kaina" yra skaičiai
            if (!is_numeric($kiekis) || !is_numeric($kaina) || !is_numeric($kategorija)) {
                echo "Neteisingas formatas. Tik skaičiai: Kiekis, Kaina ir Kategorija";
            } else {
                // Apskaičiuoti 10% nuo įvestos kainos
                $kaina_su_mokestiu = $kaina * 1.1; // Pridedame 10%

                // Patikrinti, ar vartotojas prisijungęs, ir gauti jo ID
                if (!isset($_SESSION['TiekejoPaskyrosID'])) {
                    // Persiunčiame vartotoją į prisijungimo puslapį arba tvarkome atvejį, kai vartotojas neprisijungęs
                    header("Location: tiekeju_prisijungimas.php");
                    exit;
                }

                $tiekejoID = $_SESSION['TiekejoPaskyrosID']; // Gauti prisijungusio tiekėjo ID iš sesijos

                // Įterpti produktą į prekes lentelę kartu su tiekėjo ID ir atnaujinta kaina
                $insert_query = "INSERT INTO prekes (Pavadinimas, Kategorija, Kiekis, Kaina, TiekejoID) 
                                VALUES ('$pavadinimas', '$kategorija', '$kiekis', '$kaina_su_mokestiu', '$tiekejoID')";

                if ($conn->query($insert_query) === TRUE) {
                    echo "Naujas produktas pridėtas sėkmingai";
                } else {
                    echo "Klaida: " . $insert_query . "<br>" . $conn->error;
                }
            }
        }
    }

    $conn->close();
    ?>

    <!-- Nuoroda grįžimui į tiekejo_pagrindinis.php -->
    <a href="tiekejo_pagrindinis.php">Grįžti</a>
</body>
</html>
