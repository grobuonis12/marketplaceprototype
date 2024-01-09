<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['role'])) {
        // Jei nepasirinktas vaidmuo, rodomas pranešimas ir nuoroda į 'index.php'
        echo "Nepasirinkote vaidmens";
        echo '<br><a href="index.php">Grįžti</a>';
    } else {
        $role = $_POST['role']; // Gaunamas pasirinktas vaidmuo

        if ($role == 'supplier') {
            header('Location: tiekeju_prisijungimas.php'); // Jei tiekėjas, nukreipimas į tiekeju_prisijungimas.php
            exit;
        } elseif ($role == 'client') {
            header('Location: kliento_prisijungimas.php'); // Jei klientas, nukreipimas į kliento_prisijungimas.php
            exit;
        }
    }
}
?>
