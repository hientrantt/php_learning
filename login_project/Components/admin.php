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

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" name="adminForn">
  <div class="d-flex justify-content-end">
    <input name="submitLogout" type="submit" value="Logout" class="btn btn-primary" />
  </div>
  <?php
  $sql = "SELECT id, name, phone, email, status, isAdmin FROM user";
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
            <th scope="col">IsAdmin</th>
          </tr>
        </thead>
        <tbody>';

      foreach ($customers as $customer) {
        echo "<tr>";
        echo '<th scope="row">' . $customer["id"] . '</th>';
        echo '<td>' . $customer["name"] ?? "" . '</td>';
        echo '<td>' . $customer["phone"] ?? "" . '</td>';
        echo '<td>' . $customer["email"] ?? "" . '</td>';
        echo '<td>' . $customer["status"] ?? "" . '</td>';
        echo '<td>' . $customer["isAdmin"] ?? "" . '</td>';
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