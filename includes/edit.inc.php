<?php session_start(); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
  unset($_SESSION["edit_errors"]);
  unset($_SESSION["form_data"]);

  $product_name = $_POST["product_name"];
  $description = $_POST["description"];
  $quantity = (int) $_POST["quantity"];
  $productId = $_POST["id"];
  $errors = [];
  $form_data = [];

  if (empty($product_name) || empty($description) || empty($quantity)) {
    $errors["empty_inputs"] = "Please fill out all fields";
    $form_data = [
      "product_name" => $product_name,
      "description" => $description,
      "quantity" => $quantity
    ];
  }

  if (!is_numeric($quantity) || $quantity <= 0) {
    $errors["invalid_quantity"] = "Invalid quantity";
    $form_data = [
      "product_name" => $product_name,
      "description" => $description,
      "quantity" => 1
    ];
  }

  if ($errors) {
    $_SESSION["edit_errors"] = $errors;
    $_SESSION["form_data"] = $form_data;
    header("Location: ../edit.php?id=" . $productId);
  } else {
    try {
      include("../config.php");
      $query = "UPDATE products SET product_name = :product_name, 
                description = :description, quantity = :quantity 
                WHERE id = :id;";
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(":product_name", $product_name);
      $stmt->bindParam(":description", $description);
      $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
      $stmt->bindParam(":id", $productId, PDO::PARAM_INT);
      $stmt->execute();
      $stmt = null;
      $pdo = null;
      header("Location: ../products.php");
    } catch (PDOException $e) {
      die("Database Error: " . $e->getMessage());
    }
  }
} else {
  header("../index.php");
}
