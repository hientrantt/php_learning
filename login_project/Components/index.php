<?php
require("./header.php");
?>

<h2>Form LOGIN</h2>

<?php

$email = $password = "";
$emailError = $passwordError = "";

if (isset($_POST['submit'])) {

    $email = testInput($_POST['loginInputEmail']);
    if (empty($email)) {
        $emailError = "Email is required";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Invalid email format";
        }
    }

    $password = htmlspecialchars($_POST['loginInputPassword']);
    if (empty($password)){
        $passwordError = "Password is required";
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

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" name="formLogin">
    <div class="form-group">
        <label for="loginInputEmail1">Email address</label>
        <input name="loginInputEmail" type="email" class="form-control" id="loginInputEmail1" value="<?php echo $email?>" placeholder="Enter email">
        <small class="form-text text-danger"><?php echo $emailError?></small>
    </div>
    <div class="form-group">
        <label for="loginInputPassword1">Password</label>
        <input name="loginInputPassword" type="password" class="form-control" id="loginInputPassword1" value="<?php echo $password?>" placeholder="Password">
        <small class="form-text text-danger"><?php echo $passwordError?></small>
    </div>
    <div class="form-group">
        <a href="https://html.form.guide/php-form/php-form-action-self/">Register</a>
    </div>
    <input name="submit" value="submit" id="loginSubmit" type="submit" class="btn btn-primary" />
</form>

<?php
include("./footer.php");
?>