<?php
if (isset($_GET["id"]) && is_numeric((int) $_GET["id"])) {
  try {
    include("config.php");
    $productId = (int) $_GET["id"];
    $query = "SELECT * FROM products WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $productId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($result)) {
      header("Location: index.php");
      die();
    }
  } catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
  }
} else {
  header("Location: products.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/general.css" />
  <link rel="stylesheet" href="./styles/delete.css?<?php time(); ?>" />
  <title><?php echo $result["product_name"]; ?> - Delete</title>
</head>

<body>
  <main>
    <section>
      <h1>Are you sure you want to delete <span><?php echo $result["product_name"]; ?></span> ?</h1>
      <form action="./includes/delete.inc.php" method="post">
        <button type="submit" name="delete" value="<?php echo $result["id"]; ?>">Yes</button>
        <a href="products.php">
          <button type="button">Cancel</button>
        </a>
      </form>
    </section>
  </main>
</body>

</html>