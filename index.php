<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="./styles/general.css" />
  <link rel="stylesheet" href="./styles/index.css?<?php echo time(); ?>" />
  <title>Amogus Inventory</title>
</head>
<?php
$create_data = isset($_SESSION["create_data"]) ? $_SESSION["create_data"] : [
  "product_name" => "",
  "description" => "",
  "quantity" => 1
];
?>

<body>
  <header>
    <div class="icon-container">
      <i class="fa-solid fa-code"></i>
      <h2>Amogus Inventory</h2>
    </div>
    <form action="search.php" method="get" class="search-container">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" placeholder="Search product..." name="keyword">
    </form>
    <a href="products.php"><i class="fa-solid fa-shop"></i></a>
  </header>
  <?php
  if (isset($_SESSION["create_errors"])) {
    $errors = $_SESSION["create_errors"];

    echo "<div class='error-message'>";
    foreach ($errors as $error) {
      echo "<p> &#183; " . $error . "</p>";
    }
    echo "</div>";
  }
  if (isset($_GET["success"]) && (bool) $_GET["success"]) {
    echo "<div class='create-success'>
            <i class='fa-solid fa-check'></i>
            <p>New product successfully added!</p>
          </div>";
  }
  ?>
  <main>
    <section>
      <h1>Create a product</h1>
      <form action="./includes/create.inc.php" method="post" class="create-form">
        <div class="input-container">
          <label for="p-name">Product Name</label>
          <input id="p-name" type="text" name="product_name" value="<?php echo $create_data["product_name"]; ?>">
        </div>
        <div class="input-container">
          <label for="description">Description</label>
          <textarea id="description" type="text" name="description" rows="8"><?php echo $create_data["description"]; ?></textarea>
        </div>
        <div class="input-container">
          <label for="quantity">Quantity</label>
          <input type="number" min="1" name="quantity" value="<?php echo $create_data["quantity"]; ?>">
        </div>
        <button type="submit">Create</button>
      </form>
    </section>
  </main>

  <script>
    const successMessage = document.querySelector(".create-success");
    if (successMessage) {
      setTimeout(() => {
        successMessage.style.display = "none";
        window.history.replaceState({}, "", window.location.pathname + "?");
      }, 2600);
    }
  </script>
</body>

</html>