<?php
require("./header.php");
?>

<h2>Confirm Delete Account</h2>

<?php
session_start();
if (!isset($_SESSION['userLogin'])) {
  header("Location: index.php");
}
?>
<?php
$name = $nameError = $status = "";

if (isset($_POST['submitDeleteAccount'])) {
  $idUserLogin = $_SESSION['userLogin']['id'];
  $sql = "SELECT * FROM user WHERE id = '$idUserLogin'";
  $_SESSION['userLogin'] = getQueryFromDb($connection, $sql)[0];

  $name = htmlspecialchars($_POST['inputName']);
  $name = str_replace(' ', '&nbsp;', $name);;
  if (empty($name)) {
    $nameError = "Name is required";
  } else if (strcmp($name, $_SESSION['userLogin']['name'])) {
    $nameError = "Username incorrect";
  }

  // print_r($_SESSION['userLogin']);
  // $userLogin['avatar'];
  // echo "test".$_SESSION['userLogin'];

  if (empty($nameError)) {
    if ($connection != null) {
      $sql = "DELETE FROM user WHERE id=?";
      $statement = $connection->prepare($sql);
      $statement->execute([$_SESSION['userLogin']['id']]);

      if (!empty($_SESSION['userLogin']['avatar'])) {
        unlink($_SESSION['userLogin']['avatar']);
      }

      session_destroy();
      header("Location: index.php");
    } else {
      $status = "No internet connect";
    }
  }
} else if (isset($_POST['submitBackPreviousPage'])) {
  header("Location: home.php");
}
?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" name="confirmDeleteAccountForn" onkeydown="return event.key != 'Enter';">
  <h4 class="text-danger text-center">Are you sure want to delete your account???</h4>

  <div class="form-group">
    <label for="inputName">Re-enter username to confirm</label>
    <input name="inputName" type="text" class="form-control <?php echo !empty($nameError) ? 'is-invalid' : '' ?>" id="inputName" value="<?php echo $name ?>" placeholder="Enter user name">
    <small class="form-text text-danger"><?php echo $nameError ?></small>
  </div>

  <div class="form-group d-flex justify-content-center">
    <small class="form-text text-warning d-block"><?php echo $status ?></small>
    <input name="submitBackPreviousPage" value="No, back home page" type="submit" class="btn btn-success me-4" />
    <input name="submitDeleteAccount" value="Yes, delete account" type="submit" class="btn btn-danger" />
  </div>

</form>

<?php
include("./footer.php");
?>