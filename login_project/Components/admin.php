<?php
require("./header.php");
?>

<?php
session_start();
if (!isset($_SESSION['userLogin']) || $_SESSION['userLogin']['isAdmin'] != 1) {
  header("Location: index.php");
}
?>

<h2>Admin Page</h2>
<?php
if (isset($_POST['submitLogout'])) {
  header("Location: logout.php");
}
?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" name="adminForn"
  onkeydown="return event.key != 'Enter';">
  <div class="d-flex justify-content-end align-items-end"">
    <a href=" ./home.php">Home page</a>
    <input name="submitLogout" type="submit" value="Logout" class="btn btn-primary" />
  </div>
  <?php
  $sql = "SELECT * FROM user";
  if ($connection != null) {
    try {
      $statement = $connection->prepare($sql);
      $statement->execute();
      $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
      $customers = $statement->fetchAll();
      echo '
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Phone</th>
            <th scope="col">Email</th>
            <th scope="col">Status</th>
            <th scope="col">Type</th>
            <th scope="col">Avatar</th>
          </tr>
        </thead>
        <tbody>';

      foreach ($customers as $customer) {
        echo "<tr>";
        echo '<th scope="row">' . $customer["id"] . '</th>';
        echo '<td>' . $customer["name"] ?? "" . '</td>';
        echo '<td>' . $customer["phone"] ?? "" . '</td>';
        echo '<td>' . $customer["email"] ?? "" . '</td>';

        if ($customer["status"] == 0) {
          $statusStr = "Block";
          $color = "text-danger";
        } else if ($customer["status"] == 1) {
          $statusStr = "Active";
          $color = "text-success";
        }
        echo '<td class='.$color.'>'. $statusStr . '</td>';

        if ($customer["isAdmin"] == 0) {
          $typeStr = "User";
        } else if ($customer["isAdmin"] == 1) {
          $typeStr = "Admin";
        }
        echo '<td>' . $typeStr . '</td>';

        if(!empty($customer["avatar"])){
          $avatarStr = '<img src=' . $customer["avatar"] . ' class="img-fluid img-thumbnai" style="height:50px" alt="avatar">';
        }else{
          $avatarStr = "Unset";
        }
        echo '<td>' . $avatarStr . '</td>';
        
        echo "</tr>";
      }

      echo '
        </tbody>
      </table>';
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
  ?>
</form>

<?php
include("./footer.php");
?>