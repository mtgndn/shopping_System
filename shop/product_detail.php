<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = (int)$_GET['id'];

// Ürün bilgilerini çek
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Ürün bulunamadı.";
    exit();
}

$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sepete ekle işlemi
    $user_id = $_SESSION['user_id'];

    // Sepette varsa miktar artır, yoksa ekle
    $check_stmt = $conn->prepare("SELECT quantity FROM carts WHERE user_id = ? AND product_id = ?");
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $check_stmt->bind_result($quantity);
        $check_stmt->fetch();
        $new_quantity = $quantity + 1;
        $update_stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $insert_stmt->bind_param("ii", $user_id, $product_id);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    $check_stmt->close();
    $conn->close();

    // Sepete ekledikten sonra products.php sayfasına yönlendir
    header("Location: products.php");
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Ürün Detayı - <?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }
        h2 {
            margin-top: 0;
            color: #222;
        }
        p {
            font-size: 17px;
            color: #555;
        }
        form {
            margin-top: 30px;
            text-align: center;
        }
        input[type="submit"] {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        a.back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <p><strong>Kategori:</strong> <?= htmlspecialchars($product['category']) ?></p>
    <p><strong>Fiyat:</strong> ₺<?= number_format($product['price'], 2, ',', '.') ?></p>
    <p><strong>Açıklama:</strong></p>
    <p><?= nl2br(htmlspecialchars($product['description'] ?? 'Açıklama bulunmamaktadır.')) ?></p>

    <form method="post">
        <input type="submit" value="Sepete Ekle">
    </form>

    <a href="products.php" class="back-link">Ürünlere Geri Dön</a>
</div>

</body>
</html>
