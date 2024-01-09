<!DOCTYPE html>
<html>
<head>
  <title>Kliento pagrindinis</title>
  <style>
    h1, p {
      text-align: center;
    }
    a, input[type="submit"] {
      display: block;
      margin: 10px auto;
      text-align: center;
      width: 200px;
      padding: 10px;
      background-color: #f0f0f0;
      text-decoration: none;
      color: #000;
      border-radius: 5px;
    }
    a:hover, input[type="submit"]:hover {
      background-color: #ccc;
    }
  </style>
</head>
<body>
  <h1>Prekės</h1>
  <p><a href="kliento_prekiu_katalogas.php">Peržiūrėti prekių katalogą</a></p>

  <h1>Mano užsakymai</h1>
  <p><a href="klientu_uzsakymai.php">Peržiūrėti savo užsakymus</a></p>

  <h1>Gautos Prekės</h1>
  <p><a href="kliento_gautos_prekes.php">Peržiūrėti gautas prekes</a></p>

  <!-- Logout -->
  <form action="logout.php" method="post">
    <input type="submit" value="Atsijungti">
  </form>
</body>
</html>
