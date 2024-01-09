<!DOCTYPE html>
<html>
<head>
    <title>Kliento prisijungimas</title>
</head>
<body>
    <h2>Kliento prisijungimas</h2>
    <!-- Forma kliento prisijungimui -->
    <form action="" method="post">
        <label for="email">El. paštas:</label><br>
        <input type="text" id="email" name="email"><br>
        <label for="password">Slaptažodis:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Prisijungti">
    </form>
    <!-- Nuoroda į naujos paskyros kūrimą -->
    <p>Neturite paskyros? <a href="kliento_nauja_paskyra.php">Susikurkite naują paskyrą</a></p>
    <!-- Nuoroda į pagrindinį puslapį -->
    <p><a href="index.php">Grįžti</a></p>
    
    <?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "ugnius";
    $password = "123";
    $dbname = "pirkimaipardavimai";

    // Establishing the database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Nepavyko prisijungti: " . $conn->connect_error);
    }

    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            echo "Laukai neužpildyti";
        } else {
            $email = $conn->real_escape_string($email);
            $password = $conn->real_escape_string($password);

            $check_email = "SELECT * FROM klientu_paskyros WHERE email='$email'";
            $result_email = $conn->query($check_email);

            if ($result_email->num_rows == 0) {
                echo "Paskyros su šiuo email nėra";
            } else {
                $check_user = "SELECT * FROM klientu_paskyros WHERE email='$email' AND slaptazodis='$password'";
                $result = $conn->query($check_user);

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $userId = $user['KlientoPaskyrosID'];
                    $_SESSION['KlientoPaskyrosID'] = $userId;
                    header("Location: kliento_pagrindinis.php");
                    exit;
                } else {
                    echo "Slaptažodis neteisingas";
                }
            }
        }
    }

    $conn->close();
}
?>