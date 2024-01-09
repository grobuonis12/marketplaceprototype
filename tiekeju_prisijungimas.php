<!DOCTYPE html>
<html>
<head>
    <title>Tiekėjo prisijungimas</title>
</head>
<body>
    <h2>Tiekėjo prisijungimas</h2>
    <!-- Prisijungimo forma tiekėjams -->
    <form action="" method="post">
        <label for="email">El. paštas:</label><br>
        <input type="text" id="email" name="email"><br>
        <label for="password">Slaptažodis:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Prisijungti">
    </form>
    <!-- Nuorodos į kitas puslapius -->
    <p>Neturite paskyros? <a href="tiekeju_nauja_paskyra.php">Susikurkite naują paskyrą</a></p>
    <p><a href="index.php">Grįžti</a></p>
    
    <?php
    // Pradedame sesiją
    session_start();
    
    // Duomenų bazės prisijungimo duomenys
    $servername = "localhost";
    $username = "ugnius";
    $password = "123";
    $dbname = "pirkimaipardavimai";
    
    // Užmezgame ryšį su duomenų baze
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Tikriname, ar prisijungimas prie duomenų bazės įvyko sėkmingai
    if ($conn->connect_error) {
        die("Prisijungimas nepavyko: " . $conn->connect_error);
    }
    
    // Tikriname, ar buvo siųstas POST užklaustymas
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Patikriname, ar formos laukai yra užpildyti
        if (empty($email) || empty($password)) {
            echo "Laukai neužpildyti";
        } else {
            $email = $conn->real_escape_string($email);
            $password = $conn->real_escape_string($password);
    
            // Tikriname, ar duomenų bazėje yra toks el. paštas
            $check_email = "SELECT * FROM tiekeju_paskyros WHERE email='$email'";
            $result_email = $conn->query($check_email);
    
            if ($result_email->num_rows == 0) {
                echo "Paskyros su šiuo el. paštu nėra";
            } else {
                // Jei el. paštas rastas, tikriname, ar slaptažodis atitinka
                $check_user = "SELECT * FROM tiekeju_paskyros WHERE email='$email' AND slaptazodis='$password'";
                $result = $conn->query($check_user);
    
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $userId = $user['TiekejoPaskyrosID'];
                    $_SESSION['TiekejoPaskyrosID'] = $userId;
                    header("Location: tiekejo_pagrindinis.php");
                    exit;
                } else {
                    echo "Slaptažodis neteisingas";
                }
            }
        }
    }
    
    // Uždarome duomenų bazės ryšį
    $conn->close();
    ?>
</body>
</html>
