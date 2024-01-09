<!DOCTYPE html>
<html>
<head>
    <title>Kliento paskyros kūrimas</title>
</head>
<body>
    <h2>Kliento paskyros kūrimas</h2>
    <!-- Forma kliento duomenims suvedimui -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="vardas">Vardas:</label><br>
        <input type="text" id="vardas" name="vardas"><br>
        <label for="pavarde">Pavardė:</label><br>
        <input type="text" id="pavarde" name="pavarde"><br>
        <label for="kontaktas">Kontaktai, numeris:</label><br>
        <input type="text" id="kontaktas" name="kontaktas"><br>
        <label for="adresas">Adresas:</label><br>
        <input type="text" id="adresas" name="adresas"><br><br>
        <label for="email">El. paštas:</label><br>
        <input type="text" id="email" name="email"><br>
        <label for="slaptazodis">Slaptažodis:</label><br>
        <input type="password" id="slaptazodis" name="slaptazodis"><br><br>
        <input type="submit" value="Sukurti paskyrą">
    </form>

    <?php
    $servername = "localhost";
    $username = "ugnius";
    $password = "123";
    $dbname = "pirkimaipardavimai";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $vardas = $_POST['vardas'];
        $pavarde = $_POST['pavarde'];
        $kontaktas = $_POST['kontaktas'];
        $email = $_POST['email'];
        $slaptazodis = $_POST['slaptazodis'];

        $adresas = $_POST['adresas'];
        if (empty($adresas) || $adresas === '0') {
            $adresas = "Dėl tikslaus adreso kreiptis Kliento tel nr.";
        }

        if (empty($vardas) || empty($pavarde) || empty($kontaktas) || empty($email) || empty($slaptazodis)) {
            echo "Ne viskas užpildyta.";
        } else {
            $check_email = "SELECT * FROM klientu_paskyros WHERE email=?";
            $stmt = $conn->prepare($check_email);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "Šis paštas jau naudojamas.";
            } else {
                // Įterpiama į klientu_paskyros tik tuomet, jei toks el. paštas dar nebuvo naudojamas
                $insert_klientu_paskyros = "INSERT INTO klientu_paskyros (email, slaptazodis) 
                                            VALUES (?, ?)";
                $stmt = $conn->prepare($insert_klientu_paskyros);
                $stmt->bind_param("ss", $email, $slaptazodis);
                $stmt->execute();

                // Gaunamas naujojo įrašo ID iš klientu_paskyros
                $last_inserted_id = $conn->insert_id;

                // Įterpiama į klientai lentelę su atitinkamais ID ir KlientoPaskyrosID
                $insert_klientai = "INSERT INTO klientai (Vardas, Pavarde, Kontaktas, KlientoID, Adresas, KlientoPaskyrosID) 
                                    VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_klientai);
                $stmt->bind_param("sssisi", $vardas, $pavarde, $kontaktas, $last_inserted_id, $adresas, $last_inserted_id);
                $stmt->execute();

                echo "Sėkminga registracija!<br>";
                echo "<a href='kliento_prisijungimas.php'>Grįžti į Kliento prisijungimo ekraną</a>";
            }
        }
    }

    $conn->close();
    ?>
</body>
</html>
