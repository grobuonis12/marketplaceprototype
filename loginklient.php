<?php
$servername = "localhost";
$username = "ugnius";
$password = "123";
$dbname = "pirkimaipardavimai";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Prisijungimas nepavyko: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Laukai neužpildyti";
    } else {
        $email = $conn->real_escape_string($email); // Saugumo tikrinimas - naudotojo įvesties apsauga
        $password = $conn->real_escape_string($password); // Saugumo tikrinimas - naudotojo įvesties apsauga

        $check_user = "SELECT * FROM Klientu_paskyros WHERE email='$email' AND slaptazodis='$password'";
        $result = $conn->query($check_user);

        if ($result->num_rows > 0) {
            // Gauti naudotojo ID
            $user = $result->fetch_assoc();
            $userId = $user['KlientoPaskyrosID'];

            // Nustatyti sesijos kintamąjį po sėkmingo prisijungimo
            $_SESSION['KlientoPaskyrosID'] = $userId;

            // Nukreipimas po sėkmingo prisijungimo
            header("Location: kliento_prekiu_katalogas.php");
            exit;
        } else {
            echo "Neteisingas el. paštas arba slaptažodis";
        }
    }
}

$conn->close();
?>
