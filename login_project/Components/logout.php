<?php
require("./header.php");
?>

<?php 
  session_start();
  session_destroy();
  header("Location: index.php");
?>

<?php
include("./footer.php");
?>