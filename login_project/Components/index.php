<?php
require("./header.php");
?>

<div>
  <h2>Form LOGIN</h2>
</div>

<?php

if(isset($_POST['submitClear'])){
    foreach($_COOKIE as $key => $value){
        setcookie($key, "", time()-1);
    }
    $email =  "";
}else{
    $email = $_COOKIE['loginEmail'] ?? "";
}

$password = "";
$emailCheck = false;
$emailError = $passwordError = $loginStatus = "";

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

    if(isset($_POST['rememberEmailCheck'])){
        $emailCheck = $_POST['rememberEmailCheck'];
        setcookie("loginEmail", $email, time() + 60*60*2); //lưu email trong 2h
    }

    if($emailError=="" && $passwordError==""){
        $sql = "SELECT * FROM user";
        $userLogin = null;
        if ($connection != null) {
            try {
                $statement = $connection->prepare($sql);
                $statement->execute();
                $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
                $users = $statement->fetchAll();

                foreach($users as $user){
                    if($user['email']==$email){
                        $userLogin = $user;       
                        break;
                    }
                }
                
                
            } catch(PDOException $e){
                echo $e->getMessage();
            }
        }

        //destroy all session
        session_start();
        session_destroy();
        
        //restart session
        session_start();
        if($userLogin!=null){
            if($password!=$userLogin['password']){
                $loginStatus = "Password wrong";
            }else if($userLogin['status']==0){
                $loginStatus = "This account was blocked";
            }else{
                $_SESSION['userLogin'] = $userLogin;
                switch($userLogin['isAdmin']){
                    case 0:
                        $loginStatus = "Customer";
                        header("Location: home.php");
                        break;
                    case 1:
                        $loginStatus = "Admin";
                        header("Location: admin.php");
                        break;
                    default:
                        $loginStatus = "Invalid";
                        break;
                }
            }
        }else{
            $loginStatus = "Account isn't exist";
        }
    }
}

?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" name="formLogin">
  <div class="form-group">
    <label for="loginInputEmail">Email address</label>
    <input name="loginInputEmail" type="email" class="form-control <?php echo !empty($emailError)?'is-invalid':''?>"
      id="loginInputEmail" value="<?php echo $email?>" placeholder="Enter email">
    <small class="form-text text-danger"><?php echo $emailError?></small>
  </div>

  <div class="form-group">
    <label for="loginInputPassword">Password</label>
    <input name="loginInputPassword" type="password"
      class="form-control <?php echo !empty($passwordError)?'is-invalid':''?>" id="loginInputPassword"
      value="<?php echo $password?>" placeholder="Password">
    <small class="form-text text-danger"><?php echo $passwordError?></small>
  </div>
  <div class="mb-3 form-check">
    <input name="rememberEmailCheck" value="true" <?php echo $emailCheck? 'checked':''?> type="checkbox"
      class="form-check-input" id="loginCheckRememberEmail">
    <label class="form-check-label" for="loginCheckRememberEmail">Remenber my email</label>
  </div>
  <div class="form-group">
    <small class="form-text text-warning d-block"><?php echo $loginStatus?></small>
    <a href="./register.php">Register</a> | <a href="./forgotPassword.php">Forgot password</a> 
  </div>
  <div class="form-group d-flex justify-content-between">
    <input name="submit" value="Login" id="loginSubmit" type="submit" class="btn btn-primary" />
    <input type="submit" value="Clear my cookies" name="submitClear" id="clearCookiesBtn" class="btn btn-warning"/>
  </div>
</form>

<?php
include("./footer.php");
?>