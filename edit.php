<?php
include("config.php");

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
  try {
    $id = (int) $_GET["id"];
    $query = "SELECT * FROM products WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
} else {
  header("Location: ./products.php");
  die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./styles/general.css" />
  <link rel="stylesheet" href="./styles/edit.css?<?php echo time(); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div class="container">
    <?php if ($product) { ?>
      <div class="form-container">
        <h2>Edit product</h2>
        <form action="" method="post">
          <div class="input-container">
            <label for="p_name">Product name</label>

          </div>
        </form>
      </div>
    <?php } else { ?>
      <div class="error">
        <h2>No products found. You've just changed the URL fucker!</h2>
        <a href="products.php">Go back</a>
      </div>
    <?php } ?>
  </div>
</body>

</html>