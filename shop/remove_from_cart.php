<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_GET['product_id'];

// Sepetten ürünü sil
$stmt = $conn->prepare("DELETE FROM carts WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: cart.php");
exit();
