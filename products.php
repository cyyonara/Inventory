<?php
include("./config.php");
session_start();
unset($_SESSION["create_errors"]);
try {
  //Implement pagination
  $page = isset($_GET["page"]) && is_numeric($_GET["page"])
    && (int) $_GET["page"] > 0 ?
    (int) $_GET["page"] : 1;
  $limit = 10;
  $offset = ($page - 1) * $limit;

  // Get products
  $query = "SELECT * FROM products LIMIT :limit OFFSET :offset;";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
  $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Get the total number of products
  $query = "SELECT COUNT(id) FROM products;";
  $countStmt = $pdo->prepare($query);
  $countStmt->execute();
  $productCount = $countStmt->fetchColumn();
  $totalPages = ceil($productCount / $limit);
  $hasNextPage = $page < $totalPages;
} catch (PDOException $e) {
  die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/general.css" />
  <link rel="stylesheet" href="./styles/products.css?<?php echo time(); ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Products</title>
</head>
<?php
$prevPage = $page === 1 || $page <= 0 ? 1 : $page - 1;
$nextPage = $hasNextPage ? $page + 1 : $page;
?>

<body>
  <div class="container">
    <?php if (count($products) > 1) { ?>
      <table>
        <thead>
          <th>Product</th>
          <th>Description</th>
          <th>Quantity</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php
          foreach ($products as $row) { ?>
            <tr class="tr-color">
              <td class="td"><?php echo $row["product_name"]; ?></td>
              <td class="td"><?php echo $row["description"]; ?> </td>
              <td class="td"><?php echo $row["quantity"]; ?></td>
              <td class="action-container">
                <button class="action-button view">View</button>
                <a href="edit.php?id=<?php echo $row["id"]; ?>">
                  <button class="action-button edit">Edit</button>
                </a>
                <button class="action-button delete">Delete</button>
              </td>
            </tr>
          <?php } ?>
          <tr>
            <td colspan="4">
              <div class="pagination-container">
                <a href="index.php" class="back">Back to home</a>
                <p>Total products: <?php echo $productCount; ?></p>
                <div class="page-button-container">
                  <a href="products.php?page=<?php echo $prevPage; ?>">
                    <button <?php if ($page === 1 || $page <= 0) echo "disabled"; ?>><i class="fa-solid fa-chevron-left"></i> Previous</button>
                  </a>
                  <span><?php echo $page . " of " . $totalPages ?> </span>
                  <a href="products.php?page=<?php echo $nextPage; ?>">
                    <button <?php if (!$hasNextPage) echo "disabled"; ?>>Next <i class="fa-solid fa-angle-right"></i></button>
                  </a>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    <?php } else { ?>
      <h1>No products at the moment.</h1>
    <?php } ?>
  </div>
</body>

</html>