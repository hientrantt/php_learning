<?php
require("./header.php");
?>

<h2>Form LOGIN</h2>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" name="formLogin">
    <div class="form-group">
        <label for="loginInputEmail1">Email address</label>
        <input name="loginInputEmail" type="email" class="form-control" id="loginInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="loginInputPassword1">Password</label>
        <input name="loginInputPassword" type="password" class="form-control" id="loginInputPassword1" placeholder="Password">
    </div>
    <div class="form-group">
        <a href="https://html.form.guide/php-form/php-form-action-self/">Register</a>
    </div>
    <input name="submit" value="submit" id="loginSubmit" type="submit" class="btn btn-primary" />
</form>

<?php
include("./footer.php");
?>