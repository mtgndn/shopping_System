<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Sepetiniz</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            padding: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .cart-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .cart-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 0.7fr;
            font-weight: 700;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            color: #555;
            user-select: none;
        }
        .cart-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 0.7fr;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            font-size: 16px;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .cart-item div {
            padding: 0 10px;
        }
        .price, .quantity, .total-price {
            text-align: center;
        }
        .total-container {
            text-align: right;
            margin-top: 25px;
            font-size: 22px;
            font-weight: 700;
            color: #2a9d8f;
        }
        .actions {
            margin-top: 35px;
            display: flex;
            justify-content: center;
            gap: 25px;
        }
        .actions a {
            text-decoration: none;
            background-color: #264653;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .actions a:hover {
            background-color: #1b2e3a;
        }
        .empty-message {
            text-align: center;
            font-size: 1.3em;
            color: #777;
            padding: 40px 0;
        }
        /* Sil link stili */
        .cart-item div:last-child a {
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 6px;
            background-color: #d9534f;
            color: white !important;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .cart-item div:last-child a:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<div class="cart-container">

<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Sepet ürünlerini çek (ürün id'si de dahil)
$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, c.quantity 
    FROM carts c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Sepetiniz</h2>";

if ($result->num_rows > 0) {
    echo "<div class='cart-header'>
            <div>Ürün Adı</div>
            <div class='price'>Birim Fiyat</div>
            <div class='quantity'>Adet</div>
            <div class='total-price'>Toplam Fiyat</div>
            <div>İşlem</div>
          </div>";

    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $line_total = $row['price'] * $row['quantity'];
        $total += $line_total;

        echo "<div class='cart-item'>";
        echo "<div>" . htmlspecialchars($row['name']) . "</div>";
        echo "<div class='price'>" . number_format($row['price'], 2, ',', '.') . " ₺</div>";
        echo "<div class='quantity'>{$row['quantity']}</div>";
        echo "<div class='total-price'>" . number_format($line_total, 2, ',', '.') . " ₺</div>";
        // Silme linki
        echo "<div style='text-align:center;'>";
        echo "<a href='remove_from_cart.php?product_id=" . intval($row['id']) . "' onclick='return confirm(\"Bu ürünü sepetten silmek istediğinize emin misiniz?\");'>Sil</a>";
        echo "</div>";
        echo "</div>";
    }
    echo "<div class='total-container'>Toplam Tutar: " . number_format($total, 2, ',', '.') . " ₺</div>";
} else {
    echo "<p class='empty-message'>Sepetinizde ürün bulunmamaktadır.</p>";
}

$stmt->close();
$conn->close();
?>

<div class="actions">
    <a href="products.php">Alışverişe Devam Et</a>
    <a href="checkout.php">Ödeme Sayfasına Git</a>
</div>

</div>

</body>
</html>
