<?php
require("./header.php");
?>

<h2>Reset password</h2>

<?php
const FORM_CONFIRM = "formFindAccount";
const FORM_RESET = "formResetPassword";

$email = $name = $phone  = $password = $confirmPassword = "";
$emailError = $nameError = $phoneError = $passwordError = $confirmPasswordError = $status = "";
$formStr = FORM_CONFIRM;

if (isset($_POST['submitConfirm'])){
  $name = htmlspecialchars($_POST['inputName']);
  if (empty($name)) {
    $nameError = "Name is required";
  }

  $email = testInput($_POST['inputEmail']);
  if (empty($email)) {
    $emailError = "Email is required";
  }else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format";
    }
}

  $phone = testInput($_POST['inputPhone']);
  if (empty($phone)) {
    $phoneError = "Phone number is required";
  }

  if(empty($emailError) && empty($nameError) && empty($phoneError)){
    $sql = "SELECT name FROM user WHERE name='$name' AND phone='$phone' AND email='$email' ;";
    $users = getQueryFromDb($connection, $sql);
    if (count($users) <= 0) {
      $status = "Account not found";
    }else{
      setcookie("forgotPassword", $name);
      $formStr = FORM_RESET;
    }
  }
}

if (isset($_POST['submitReset'])) {
  $formStr = FORM_RESET;
  $password = htmlspecialchars($_POST['inputPassword']);
  if (empty($password)) {
    $passwordError = "Password is required";
  }

  $confirmPassword = htmlspecialchars($_POST['inputConfirmPassword']);
  if (empty($confirmPassword)) {
    $confirmPasswordError = "Confirm Password is required";
  } else {
    if (strcmp($password, $confirmPassword)) {
      $confirmPasswordError = "Confirm Password is different from password";
    }
  }

  if(empty($passwordError) && empty($confirmPasswordError)) {
    $sql = "UPDATE user SET password=? WHERE name=?";
    $statement = $connection->prepare($sql);
    $statement->execute([$password, $_COOKIE['forgotPassword']]);
    setcookie('forgotPassword', "");
    header("Location: index.php");
  }
}
?>

<?php
if (!strcmp($formStr, FORM_CONFIRM)) {
?>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" name="<?echo FORM_CONFIRM?>">
    <div class="form-group">
      <label for="inputName">User name</label>
      <input name="inputName" type="text" class="form-control <?php echo !empty($nameError) ? 'is-invalid' : '' ?>" id="inputName" value="<?php echo $name ?>" placeholder="Enter user name">
      <small class="form-text text-danger"><?php echo $nameError ?></small>
    </div>

    <div class="form-group">
      <label for="inputEmail">Email address</label>
      <input name="inputEmail" type="email" class="form-control <?php echo !empty($emailError) ? 'is-invalid' : '' ?>" id="inputEmail" value="<?php echo $email ?>" placeholder="Enter email">
      <small class="form-text text-danger"><?php echo $emailError ?></small>
    </div>

    <div class="form-group">
      <label for="inputPhone">Phone number</label>
      <input name="inputPhone" type="phone" class="form-control <?php echo !empty($phoneError) ? 'is-invalid' : '' ?>" id="inputPhone" value="<?php echo $phone ?>" placeholder="Enter phone number">
      <small class="form-text text-danger"><?php echo $phoneError ?></small>
    </div>

    <div class="form-group">
      <small class="form-text text-warning d-block"><?php echo $status ?></small>
      <a href="./index.php">Login</a> | <a href="./register.php">Register</a>
    </div>

    <div class="form-group">
      <input name="submitConfirm" value="Confirm" type="submit" class="btn btn-primary" />
    </div>
  </form>
<?php
} else if (!strcmp($formStr, FORM_RESET)) {
?>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" name="<?echo FORM_RESET?>">
    <div class="form-group">
      <label for="inputPassword">Password</label>
      <input name="inputPassword" type="password" class="form-control <?php echo !empty($passwordError) ? 'is-invalid' : '' ?>" id="inputPassword" value="<?php echo $password ?>" placeholder="Password">
      <small class="form-text text-danger"><?php echo $passwordError ?></small>
    </div>

    <div class="form-group">
      <label for="inputConfirmPassword">Confirm password</label>
      <input name="inputConfirmPassword" type="password" class="form-control <?php echo !empty($confirmPasswordError) ? 'is-invalid' : '' ?>" id="inputConfirmPassword" value="<?php echo $confirmPassword ?>" placeholder="Confirm password">
      <small class="form-text text-danger"><?php echo $confirmPasswordError ?></small>
    </div>

    <div class="form-group">
      <small class="form-text text-warning d-block"><?php echo $status ?></small>
      <a href="./index.php">Login</a> | <a href="./register.php">Register</a>
    </div>
    <div class="form-group">
      <input name="submitReset" value="Reset Password" type="submit" class="btn btn-primary" />
    </div>
  </form>
<?php
}
?>
<?php
include("./footer.php");
?>