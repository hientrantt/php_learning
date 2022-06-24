<?php
require("./header.php");
?>

<h2>Admin Page</h2>
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
      echo '<th scope="row">'.$customer["id"].'</th>';
      echo '<td>'.$customer["name"] ?? "" .'</td>';
      echo '<td>'.$customer["phone"] ?? "" .'</td>';
      echo '<td>'.$customer["email"] ?? "" .'</td>';
      echo '<td>'.$customer["status"] ?? "" .'</td>';
      echo '<td>'.$customer["isAdmin"] ?? "" .'</td>';

      // $name = $customer['name'] ?? "";
      // $phone = $customer['phone'] ?? "";
      // $email = $customer['email'] ?? "";
      // $status = $customer['status'] ?? "";
      // $isAdmin = $customer['isAdmin'] ?? "";
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

<?php
include("./footer.php");
?>