<?php
require("./header.php");
?>

<?php
session_start();
if (!isset($_SESSION['userLogin'])) {
  header("Location: index.php");
}
?>

<?php
if (isset($_POST['submitDeleteAccount'])) {
  header("Location: deleteAccount.php");
}
?>

<h2>Edit Profile</h2>

<?php
if (isset($_POST['submitLogout'])) {
  header("Location: logout.php");
}
?>

<?
$idUserLogin = $_SESSION['userLogin']['id'];
$sql = "SELECT * FROM user WHERE id='$idUserLogin';";
$userLogin = getQueryFromDb($connection, $sql)[0];

$name = $userLogin['name'];
$phone = $userLogin['phone'];
$email = $userLogin['email'];
$avatar = $userLogin['avatar'];
$location_with_name = $avatar; //avatar address

$emailError = $nameError = $phoneError = $avatarError = $status = "";

if (isset($_POST['submitDeleteAvatar'])) {
  $name = isset($_POST['inputName']) ? htmlspecialchars($_POST['inputName']) : $name;
  $phone = isset($_POST['inputPhone']) ? htmlspecialchars($_POST['inputPhone']) : $phone;
  $email = isset($_POST['inputEmail']) ? htmlspecialchars($_POST['inputEmail']) : $email;
  // $avatar = "";
  $location_with_name = "";

  if ($connection != null) {

    $query = "UPDATE user SET avatar=? WHERE id=?";
    $statement = $connection->prepare($query);
    $statement->execute([$location_with_name, $userLogin['id']]);

    $query = "SELECT * FROM user WHERE id = '$idUserLogin'";
    $userLogin = getQueryFromDb($connection, $query)[0];
    $_SESSION['userLogin'] = $userLogin;

    if (!empty($avatar)) {
      unlink($avatar);
    }
    $avatar = "";
  } else {
    $statement = "No internet connection";
  }
}

if (isset($_POST['submitSave'])) {
  $name =  testInput($_POST['inputName']);
  $name = str_replace(' ', '&nbsp;', $name);;
  if (empty($name)) {
    $nameError = "Name is required";
  } else {
    $sql = "SELECT id, name FROM user WHERE name='$name';";
    $userQuery = getQueryFromDb($connection, $sql);
    if (count($userQuery) > 0 && $userLogin['id'] != $userQuery[0]['id']) {
      $nameError = "Name was exist";
    }
  }

  $email = testInput($_POST['inputEmail']);
  if (empty($email)) {
    $emailError = "Email is required";
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailError = "Invalid email format";
    } else {
      $sql = "SELECT id, name FROM user WHERE email='$email';";
      $userQuery = getQueryFromDb($connection, $sql);
      if (count($userQuery) > 0 && $userLogin['id'] != $userQuery[0]['id']) {
        $emailError = "Email was exist";
      }
    }
  }

  $phone = testInput($_POST['inputPhone']);
  if (empty($phone)) {
    $phoneError = "Phone number is required";
  } else {
    $sql = "SELECT id, name FROM user WHERE phone='$phone';";
    $userQuery = getQueryFromDb($connection, $sql);
    if (count($userQuery) > 0 && $userLogin['id'] != $userQuery[0]['id']) {
      $phoneError = "Phone number was exist";
    }
  }

  if (!empty($_FILES['inputAvatar']['name'])) {
    $file       = $_FILES['inputAvatar'];
    $nameFile       = $file['name'];
    $temporary_file = $file['tmp_name']; // temporary path

    $finfo       = finfo_open(FILEINFO_MIME_TYPE);
    $file_type  = finfo_file($finfo, $temporary_file);
    $valid_extension = array("image/jpeg", "image/png", "image/jpg");
    if (!in_array($file_type, $valid_extension)) {
      $avatarError =  "This is not an image";
    } else if ($file['size'] > 2000000) {
      $avatarError = "File image is too large, Upload less than or equal 2MB";
    } 
    if(empty($avatarError)){
      // create new avater image
      $str_to_arry        = explode('.', $nameFile);
      $extension          = end($str_to_arry); // get extension of the file.
      $upload_location     = "../Uploads/"; // targeted location
      $new_nameFile       = $name . "-avt-" . time() . "." . $extension; // unique file name
      $location_with_name = $upload_location . $new_nameFile; // finel new file
      if (!move_uploaded_file($temporary_file, $location_with_name)) {
        $avatarError = "Upload avatar invalid";
      }
    }
  }

  if (empty($emailError) && empty($nameError) && empty($phoneError) && empty($avatarError)) {
    // Execute query
    if ($connection != null) {
      $query = "UPDATE user SET name=?, email=?, phone=?, avatar=? WHERE id=?";
      $statement = $connection->prepare($query);
      $statement->execute([$name, $email, $phone, $location_with_name, $userLogin['id']]);

      // delete old avatar image
      if (!empty($avatar) && strcmp($avatar, $location_with_name)) {
        unlink($avatar);
      }
      // assign new avatar
      $avatar = $location_with_name;
    } else {
      $status = "No internet connection";
    }
  }
}

?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" name="homeForn" enctype="multipart/form-data">

  <div class="d-flex justify-content-end">
    <input name="submitLogout" type="submit" value="Logout" class="btn btn-primary" />
    <input name="submitDeleteAccount" value="Delete Account" type="submit" class="btn btn-danger"/>
  </div>

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
    <label>Avatar</label>
    <?php if (!empty($avatar))
      echo '
      <input name="submitDeleteAvatar" value="X" type="submit" class="btn btn-warning float-end"/>
      <div>
        <img src=' . $avatar . ' class="img-fluid img-thumbnai w-25 " alt="avatar">
      </div>
      <label for="inputAvatar">Change avatar</label>
      ';
    ?>
    <input name="inputAvatar" type="file" class="form-control <?php echo !empty($avatarError) ? 'is-invalid' : '' ?>" id="inputAvatar" />
    <small class="form-text text-danger"><?php echo $avatarError ?></small>
  </div>

  <div class="form-group">
    <small class="form-text text-warning d-block"><?php echo $status?></small>
    <input name="submitSave" value="Save" type="submit" class="btn btn-primary" />
  </div>
</form>

<?php
include("./footer.php");
?>