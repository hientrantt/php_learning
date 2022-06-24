<?php
require("./header.php");
?>

<div>
  <h2>Form REGISTER</h2>
</div>

<?php

$email = $name = $password = $confirmPassword = "";
$emailError = $nameError = $passwordError = $confirmPasswordError = $registerStatus = "";

if (isset($_POST['submit'])) {

  $name = htmlspecialchars($_POST['registerInputName']);
  if (empty($name)) {
    $nameError = "Name is required";
  } else {
    $sql = "SELECT name FROM user WHERE name='$name';";
    if ($connection != null) {
      try {
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $users = $statement->fetchAll();

        if (count($users) > 0) {
          $nameError = "Name was exist";
        }
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    }
  }

  $email = testInput($_POST['registerInputEmail']);
  if (empty($email)) {
    $emailError = "Email is required";
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailError = "Invalid email format";
    } else {
      $sql = "SELECT name FROM user WHERE email='$email';";
      if ($connection != null) {
        try {
          $statement = $connection->prepare($sql);
          $statement->execute();
          $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
          $users = $statement->fetchAll();

          if (count($users) > 0) {
            $emailError = "Email was exist";
          }
        } catch (PDOException $e) {
          echo $e->getMessage();
        }
      }
    }
  }

  $password = htmlspecialchars($_POST['registerInputPassword']);
  if (empty($password)) {
    $passwordError = "Password is required";
  }

  $confirmPassword = htmlspecialchars($_POST['registerInputConfirmPassword']);
  if (empty($confirmPassword)) {
    $confirmPasswordError = "Confirm Password is required";
  }else{
    if(strcmp($password, $confirmPassword)){
      $confirmPasswordError = "Confirm Password is different from password";
    }
  }

  if (empty($emailError) && empty($nameError) && empty($passwordError) && empty($confirmPasswordError)) {
    if ($connection == null) {
      $registerStatus="No network connection";
    }else{
      $statusDefault = 1;
      $isAdminDefault = 0;

      $sql = "INSERT INTO user (name, email, password, status, isAdmin) VALUES (?,?,?,?,?)";
      $statement= $connection->prepare($sql);
      $statement->execute([$name, $email, $password, $statusDefault, $isAdminDefault]);
      header("Location: index.php");
    }
  }
}

function testInput($inputValue)
{
  $inputValue = trim($inputValue);
  $inputValue = stripslashes($inputValue);
  $inputValue = htmlspecialchars($inputValue);
  return $inputValue;
}

?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" name="formRegister">
  <div class="form-group">
    <label for="registerInputName">User name</label>
    <input name="registerInputName" type="text" class="form-control <?php echo !empty($nameError) ? 'is-invalid' : '' ?>" id="registerInputName" value="<?php echo $name ?>" placeholder="Enter user name">
    <small class="form-text text-danger"><?php echo $nameError ?></small>
  </div>

  <div class="form-group">
    <label for="registerInputEmail">Email address</label>
    <input name="registerInputEmail" type="email" class="form-control <?php echo !empty($emailError) ? 'is-invalid' : '' ?>" id="registerInputEmail" value="<?php echo $email ?>" placeholder="Enter email">
    <small class="form-text text-danger"><?php echo $emailError ?></small>
  </div>

  <div class="form-group">
    <label for="registerInputPassword">Password</label>
    <input name="registerInputPassword" type="password" class="form-control <?php echo !empty($passwordError) ? 'is-invalid' : '' ?>" id="registerInputPassword" value="<?php echo $password ?>" placeholder="Password">
    <small class="form-text text-danger"><?php echo $passwordError ?></small>
  </div>

  <div class="form-group">
    <label for="registerInputConfirmPassword">Confirm password</label>
    <input name="registerInputConfirmPassword" type="password" class="form-control <?php echo !empty($confirmPasswordError) ? 'is-invalid' : '' ?>" id="registerInputConfirmPassword" value="<?php echo $confirmPassword ?>" placeholder="Confirm password">
    <small class="form-text text-danger"><?php echo $confirmPasswordError ?></small>
  </div>

  <div class="form-group">
    <small class="form-text text-warning d-block"><?php echo $registerStatus ?></small>
    <a href="./index.php">Login</a>
  </div>
  <div class="form-group">
    <input name="submit" value="Submit" id="registerSubmit" type="submit" class="btn btn-primary" />
  </div>
</form>

<?php
include("./footer.php");
?>