<?php
session_start();
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
  <title><?php echo $product["product_name"]; ?> - Edit </title>
</head>

<body>
  <?php
  $form_data = isset($_SESSION["form_data"]) ? $_SESSION["form_data"] : $product;
  $productId = $product["id"];

  if (isset($_SESSION["edit_errors"])) {
    $errors = $_SESSION["edit_errors"];
    echo "<div class='error-message'>";
    foreach ($errors as $error) {
      echo "<p>&#183; " . $error . "</p>";
    }
    echo "</div>";
  }

  ?>
  <main class="container">
    <?php if ($product) { ?>
      <div class="form-container">
        <div class="hd">
          <h1>Edit product</h1>
          <a href="./products.php">Cancel</a>
        </div>
        <form action="includes/edit.inc.php" method="post">
          <div class="input-container">
            <label for="p_name">Product name</label>
            <input id="p_name" type="text" name="product_name" value="<?php echo $form_data["product_name"] ?>" />
          </div>
          <div class="input-container">
            <label for="description">Description</label>
            <textarea id="description" name="description" cols="30" rows="10"><?php echo $form_data["description"]; ?></textarea>
          </div>
          <div class="input-container">
            <label for="quantity">Quantity</label>
            <input id="quantity" type="number" name="quantity" value="<?php echo $form_data["quantity"]; ?>">
          </div>
          <button type="submit" name="id" value="<?php echo $productId; ?>">Edit</button>
        </form>
      </div>
    <?php } else { ?>
      <div class="error">
        <h2>No products found. You've just changed the URL.</h2>
        <a href="products.php">Go back</a>
      </div>
    <?php } ?>
  </main>
</body>

<?php
unset($_SESSION["edit_errors"]);
unset($_SESSION["form_data"]);
?>

</html>