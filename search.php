<?php
include("config.php");

if (isset($_GET["keyword"])) {
  try {
    $keyword = "%" . $_GET["keyword"] . "%";
    $query = "SELECT * FROM products WHERE product_name LIKE :keyword;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":keyword", $keyword);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
  }
} else {
  header("Location: index.php");
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/general.css" />
  <link rel="stylesheet" href="./styles/search.css?<?php echo time(); ?>" />
  <title><?php echo $_GET["keyword"]; ?> - Search</title>
</head>

<body>
  <main class="container">
    <section>
      <h2>
        <?php
        $resultCount = count($results);
        $keyword = $_GET["keyword"];
        $result = $resultCount > 1 ? "results" : "result";
        echo "Search results for {$keyword} : {$resultCount} {$result}";
        ?>
      </h2>
      <div class="result-container">
        <?php foreach ($results as $row) { ?>
          <div class="result">
            <span><?php echo $row["product_name"]; ?></span>
            <div class="button-container">
              <a href="edit.php?id=<?php echo $row["id"]; ?>"><button>Edit</button></a>
              <a href="delete.php?id=<?php echo $row["id"]; ?>"><button>Delete</button></a>
            </div>
          </div>
        <?php } ?>
      </div>
      <a href="index.php">Go back</a>
    </section>
  </main>
</body>

</html>