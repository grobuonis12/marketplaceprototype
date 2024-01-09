<!DOCTYPE html>
<html>
<head>
  <title>Tiekėjo paskyros kūrimas</title>
</head>
<body>
  <h2>Tiekėjo paskyros kūrimas</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="pavadinimas">Pavadinimas:</label><br>
    <input type="text" id="pavadinimas" name="pavadinimas"><br>
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
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $servername = "localhost";
      $username = "ugnius";
      $password = "123";
      $dbname = "pirkimaipardavimai";

      $conn = new mysqli($servername, $username, $password, $dbname);

      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $pavadinimas = $_POST['pavadinimas'];
      $kontaktas = $_POST['kontaktas'];
      $adresas = $_POST['adresas'];
      $email = $_POST['email'];
      $slaptazodis = $_POST['slaptazodis'];

      if (empty($pavadinimas) || empty($kontaktas) || empty($adresas) || empty($email) || empty($slaptazodis)) {
          echo "Ne viskas užpildyta.";
      } else {
          $check_email = "SELECT * FROM tiekeju_paskyros WHERE email=?";
          $stmt = $conn->prepare($check_email);
          $stmt->bind_param("s", $email);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              echo "Šis paštas jau naudojamas.";
          } else {
             
              $insert_tiekeju_paskyros = "INSERT INTO tiekeju_paskyros (email, slaptazodis) 
                                          VALUES (?, ?)";
              $stmt = $conn->prepare($insert_tiekeju_paskyros);
              $stmt->bind_param("ss", $email, $slaptazodis);
              $stmt->execute();

             
              $last_inserted_id = $conn->insert_id;

              $insert_tiekejai = "INSERT INTO tiekejai (TiekejoID, Pavadinimas, Kontaktas, TiekejoPaskyrosID, Adresas) 
                                  VALUES (?, ?, ?, ?, ?)";
              $stmt = $conn->prepare($insert_tiekejai);
              $stmt->bind_param("isiss", $last_inserted_id, $pavadinimas, $kontaktas, $last_inserted_id, $adresas);
              $stmt->execute();

              echo "Sėkmingai sukurta paskyra!<br>";
              echo "<a href='tiekeju_prisijungimas.php'>Grįžti į Tiekėjo prisijungimo ekraną</a>";
          }
      }

      $conn->close();
  }
  ?>
</body>
</html>
