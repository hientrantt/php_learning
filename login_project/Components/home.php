<?php
require("./header.php");
?>

<?php
session_start();
if (!isset($_SESSION['userLogin']) || $_SESSION['userLogin']['isAdmin'] != 0) {
  header("Location: index.php");
}
?>

<h2>Home Page</h2>

<?php
if (isset($_POST['submitLogout'])) {
  header("Location: logout.php");
}
?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" name="homeForn">
  <div class="d-flex justify-content-end">
    <input name="submitLogout" type="submit" value="Logout" class="btn btn-primary" />
  </div>
  <!-- todo -->
</form>

<?php
include("./footer.php");
?>