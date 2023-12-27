<?php session_start(); ?>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  unset($_SESSION["create_data"]);
  unset($_SESSION["create_errors"]);
  $productName = $_POST["product_name"];
  $description = $_POST["description"];
  $quantity = $_POST["quantity"];
  $create_data = [
    "product_name" => $productName,
    "description" => $description,
    "quantity" => $quantity
  ];
  $errors = [];

  if (empty($productName) || empty($description) || empty($quantity)) {
    $errors["missing_input"] = "Please fill out all fields!";
  }

  if (!is_numeric($quantity) || $quantity <= 0) {
    $errors["quantity_invalid"] = "Invalid quantity";
    $create_data["quantity"] = 1;
  }

  if ($errors) {
    $_SESSION["create_data"] = $create_data;
    $_SESSION["create_errors"] = $errors;
    header("Location: ../index.php");
    die();
  } else {
    try {
      include("../config.php");
      $query = "INSERT INTO products(product_name, description, quantity) 
                VALUES(:name, :description, :quantity);";
      $stmt = $pdo->prepare($query);
      $stmt->execute([
        "name" => $productName,
        "description" => $description,
        "quantity" => $quantity
      ]);
      header("Location: ../index.php?success=true");
      die();
    } catch (PDOException $e) {
      $errors["database_error"] = "Database Error: " . $e->getMessage();
      $_SESSION["create_data"] = $create_data;
      $_SESSION["create_errors"] = $errors;
      header("Location: ../index.php");
    }
  }
} else {
  header("Location: ../index.php");
  die();
}

?>