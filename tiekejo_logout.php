<?php
session_start();
session_unset();
session_destroy();
header("Location: tiekeju_prisijungimas.php");
exit;
?>