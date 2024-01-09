<!DOCTYPE html>
<html lang="lt"> <!-- Lithuanian language -->
<head>
  <title>Tiekėjo Pagrindinis</title>
  <link rel="stylesheet" type="text/css" href="style.css">
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
  <h1>Prekių pridėjimas</h1>
  <a href="tiekejo_prekiu_pridejimas.php">Prekių pridėjimas</a>
  <h1>Peržiūrėti tiekėjo prekes</h1>
  <a href="tiekejo_prekes.php">Peržiūrėti prekes</a>
  
  <!-- Užsakytos prekes link -->
  <h1>Užsakytos prekės</h1>
  <a href="tiekejo_uzsakytos_prekes.php">Peržiūrėti užsakytas prekes</a>
  
  <!-- Pristatytos prekės link -->
  <h1>Pristatytos prekės</h1>
  <a href="tiekejo_pristatytos_prekes.php">Peržiūrėti pristatytas prekes</a>

  <!-- Logout button -->
  <form action="tiekejo_logout.php" method="post">
    <input type="submit" value="Atsijungti">
  </form>
</body>
</html>
