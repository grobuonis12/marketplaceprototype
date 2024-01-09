<?php
session_start();

if (!isset($_SESSION['supplierDetails'])) {
    header("Location: kliento_pagrindinis.php");
    exit();
}

$supplierDetails = $_SESSION['supplierDetails'];
unset($_SESSION['supplierDetails']); // anuliuoti sesija po duomenų gavimo

$servername = "localhost";
$username = "ugnius";
$password = "123";
$dbname = "pirkimaipardavimai";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Nepavyko prisijungti: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tiekėjo Informacija</title>
</head>
<body>
    
    <div>
        <?php
            echo $supplierDetails;
            
            $tiekejoId = ''; // ištraukti TiekejoID iš $supplierDetails string
            preg_match('/TiekejoID:\s*([\d]+)/', $supplierDetails, $matches);
            if (count($matches) > 1) {
                $tiekejoId = $matches[1];
            }

            
            $kontaktasQuery = "SELECT Kontaktas FROM tiekejai WHERE TiekejoID = '$tiekejoId'";
            $kontaktasResult = $conn->query($kontaktasQuery);
            
            if ($kontaktasResult && $kontaktasResult->num_rows > 0) {
                $row = $kontaktasResult->fetch_assoc();
                echo "<p>Kontaktas: " . $row['Kontaktas'] . "</p>";
            } else {
                echo "<p>Kontaktas nerastas.</p>";
            }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
