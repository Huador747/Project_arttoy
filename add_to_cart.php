<?php
include("include.php");
// Start session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product_id is provided in the request
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    // Validate quantity (must be positive)
    if ($quantity <= 0) {
        $quantity = 1;
    }
    
    // Add to cart or update quantity if already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    // Redirect based on request type
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // AJAX request
        $cart_count = array_sum($_SESSION['cart']);
        echo json_encode([
            'success' => true,
            'message' => 'เพิ่มสินค้าลงในตะกร้าเรียบร้อยแล้ว',
            'cart_count' => $cart_count
        ]);
        exit;
    } else {
        // Normal request - redirect to previous page or cart
        $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'cart.php';
        header('Location: ' . $redirect);
        exit;
    }
} elseif (isset($_GET['product_id'])) {
    // Alternative handling for GET requests
    $product_id = intval($_GET['product_id']);
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
    
    // Validate quantity
    if ($quantity <= 0) {
        $quantity = 1;
    }
    
    // Add to cart or update quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    // Redirect to cart or specified page
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'cart.php';
    header('Location: ' . $redirect);
    exit;
} else {
    // No product_id provided
    $error_message = "ไม่พบข้อมูลสินค้า กรุณาลองใหม่อีกครั้ง";
    
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // AJAX request
        echo json_encode([
            'success' => false,
            'message' => $error_message
        ]);
        exit;
    } else {
        // Redirect with error message
        $_SESSION['error_message'] = $error_message;
        header('Location: products.php');
        exit;
    }
}
?>