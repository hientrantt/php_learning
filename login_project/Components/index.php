<?php
require("./header.php");
?>

<h2>Form LOGIN</h2>

<?php

$email = $password = "";
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

    if($emailError=="" && $passwordError==""){
        $sql = "SELECT name, email, password, status, isAdmin FROM user";
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

        if($userLogin!=null){
            if($password!=$userLogin['password']){
                $loginStatus = "Password wrong";
            }else if($userLogin['status']==0){
                $loginStatus = "This account was blocked";
            }else{
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
        <input name="loginInputEmail" type="email" class="form-control <?php echo !empty($emailError)?'is-invalid':''?>" id="loginInputEmail1" value="<?php echo $email?>" placeholder="Enter email">
        <small class="form-text text-danger"><?php echo $emailError?></small>
    </div>
    <div class="form-group">
        <label for="loginInputPassword1">Password</label>
        <input name="loginInputPassword" type="password" class="form-control <?php echo !empty($passwordError)?'is-invalid':''?>" id="loginInputPassword1" value="<?php echo $password?>" placeholder="Password">
        <small class="form-text text-danger"><?php echo $passwordError?></small>
    </div>
    <div class="form-group">
        <small class="form-text text-warning d-block"><?php echo $loginStatus?></small>
        <a href="./register.php">Register</a>
    </div>
    <input name="submit" value="submit" id="loginSubmit" type="submit" class="btn btn-primary" />
</form>

<?php
include("./footer.php");
?>