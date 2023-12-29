<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"]) && is_numeric($_POST["delete"])) {
  try {
    include("../config.php");
    $productId = (int) $_POST["delete"];
    $query = "DELETE FROM products WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $productId, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: ../products.php");
    die();
  } catch (PDOException $e) {
    die('Database Error :' . $e->getMessage());
  }
} else {
  header("Location: ../index.php");
}
