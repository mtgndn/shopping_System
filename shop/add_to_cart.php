<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
    header("Location: products.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];

// Ürün sepette var mı kontrolü
$stmt = $conn->prepare("SELECT quantity FROM carts WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($quantity);
    $stmt->fetch();
    $stmt->close();

    $new_quantity = $quantity + 1;
    $update_stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
    $update_stmt->execute();
    $update_stmt->close();
} else {
    $stmt->close();
    $insert_stmt = $conn->prepare("INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $insert_stmt->bind_param("ii", $user_id, $product_id);
    $insert_stmt->execute();
    $insert_stmt->close();
}

$conn->close();

// İşlem sonrası products.php sayfasına yönlendir
header("Location: products.php");
exit();
