<?php
session_start();
include 'config.php';


$query = $_GET['q'] ?? '';
$result = $conn->query("SELECT * FROM products WHERE name LIKE '%$query%' OR category LIKE '%$query%'");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Ürün Arama</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="nav">
  <a href="index.php">Ürünler</a>
  <a href="cart.php">Sepetim</a>
  <a href="search.php">Ara</a>
  <a href="logout.php">Çıkış</a>
</div>

<div class="container">
  <h1>Ürün Ara</h1>
  <form method="get">
    <input type="text" name="q" placeholder="Ürün adı veya kategori" value="<?= htmlspecialchars($query) ?>">
    <button type="submit">Ara</button>
  </form>

  <?php while($row = $result->fetch_assoc()): ?>
    <div class="product">
      <h3><?= $row['name'] ?></h3>
      <p>Kategori: <?= $row['category'] ?></p>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>
