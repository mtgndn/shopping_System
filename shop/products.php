<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    // Güvenli prepared statement ile arama yapılıyor
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%') OR category LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ürünler</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        nav { margin-bottom: 20px; display: flex; justify-content: space-between; max-width: 900px; margin-left: auto; margin-right: auto; }
        nav a { margin-right: 15px; text-decoration: none; color: #007bff; font-weight: bold; padding: 10px 15px; border-radius: 8px; background: #e7f1ff; transition: background-color 0.3s ease; }
        nav a:hover { background-color: #c4ddff; }
        .search-bar { text-align: center; margin-bottom: 30px; }
        .search-bar input[type="text"] { width: 300px; padding: 10px; border-radius: 8px; border: 1px solid #ccc; }
        .search-bar input[type="submit"] { padding: 10px 20px; border: none; background-color: #007bff; color: white; border-radius: 8px; cursor: pointer; }
        .product-list { max-width: 900px; margin: auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .product { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .product h3 { margin-top: 0; }
        .product p { margin: 8px 0; }
        .product form input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .product form input[type="submit"]:hover {
            background-color: #218838;
        }
        p.no-products {
            text-align: center;
            font-size: 1.2em;
            color: #555;
        }
    </style>
</head>
<body>

<nav>
    <div>
        <a href="index.php">Ürünleri Görüntüle</a>
    </div>
    <div>
        <a href="cart.php">🛒 Sepetim</a>
        <a href="logout.php">Çıkış Yap</a>
    </div>
</nav>

<div class="search-bar">
    <form method="get" action="products.php">
        <input type="text" name="search" placeholder="Ürün adı veya kategori..." value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
        <input type="submit" value="Ara">
    </form>
</div>

<div class="product-list">
<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p>Kategori: " . htmlspecialchars($row['category']) . "</p>";
        echo "<p>Fiyat: ₺" . number_format($row['price'], 2, ',', '.') . "</p>";
        echo "<form method='post' action='add_to_cart.php'>";
        echo "<input type='hidden' name='product_id' value='" . intval($row['id']) . "'>";
        echo "<input type='submit' value='Sepete Ekle'>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "<p class='no-products'>Ürün bulunamadı.</p>";
}

if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
</div>

</body>
</html>
