<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit;
}

// Check if product_id was sent
if (!isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'No product specified']);
    exit;
}

$product_id = (int)$_POST['product_id'];
$user_id = $_SESSION['user_id'];

try {
    // Check if product exists and is active
    $stmt = $conn->prepare("SELECT id, price FROM products WHERE id = ? AND active = 1");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    // Check if item already exists in cart
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        // Update quantity if item exists
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
        $stmt->execute([$cart_item['id']]);
    } else {
        // Add new item to cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $product_id]);
    }

    echo json_encode(['success' => true, 'message' => 'Product added to cart']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    error_log($e->getMessage());
}
?>
