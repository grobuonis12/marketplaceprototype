<!DOCTYPE html>
<html>
<head>
  <title>Pasirinkite vaidmenį</title>
</head>
<body>
  <h2>Pasirinkite savo vaidmenį:</h2>
  <form action="redirect.php" method="post">
    <label for="role">Jūs esate:</label><br>
    <input type="radio" id="supplier" name="role" value="supplier">
    <label for="supplier">Tiekėjas</label><br>
    <input type="radio" id="client" name="role" value="client">
    <label for="client">Klientas</label><br><br>
    <input type="submit" name="submit" value="Pasirinkti">
  </form>

  <?php
  // Tikrinimas, ar forma buvo siunčiama POST metodu
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tikrinimas, ar pasirinktas vaidmuo
    if (!isset($_POST['role'])) {
      // Jei vaidmuo nepasirinktas, rodomas pranešimas
      echo '<script>alert("Nepasirinkote vaidmens");</script>';
    } else {
      // Jei vaidmuo pasirinktas, galima tęsti formos apdorojimą
      // Čia galima nukreipti, ar atlikti kitus veiksmus
    }
  }
  ?>
</body>
</html>
