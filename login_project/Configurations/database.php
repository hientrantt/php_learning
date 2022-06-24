<?php
  // PDO: PHP DATA OBJECT
  define('DATABASE_SERVER', '127.0.0.1');
  define('DATABASE_USER', 'root');
  define('DATABASE_PASSWORD', '123Abc@#$');
  define('DATABASE_NAME', 'php_learning');
  $connection = null;

  try{
    $connection = new PDO("mysql:host=".DATABASE_SERVER.";dbname=".DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "connected successfully";
  } catch(PDOException $e){
    $connection = null;
    // echo "connection failed: ".$e->getMessage();
  } finally{
    // auto executed
  }

  function getQueryFromDb($connection, $queryStatement){
    $list = null;
    if ($connection != null) {
      try {
        $statement = $connection->prepare($queryStatement);
        $statement->execute();
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $list = $statement->fetchAll();
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    }
    return $list;
  }
?>