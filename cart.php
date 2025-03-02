<?php
include("include.php");
session_start();

// ตัวแปรสำหรับการเชื่อมต่อฐานข้อมูลหรือการจัดการอื่นๆ
$cart_count = 0; // กำหนดจำนวนสินค้าที่อยู่ในตะกร้า
$total_price = 0; // ราคารวมทั้งหมด

// สมมติข้อมูลสินค้าจากฐานข้อมูล (ในระบบจริงควรดึงจากฐานข้อมูล)
$products = [
    1 => ['id' => 1, 'name' => 'Skullpanda City of Night Series', 'price' => 380.00, 'image' => 'img/SKULLPANDA/SKULLPANDA1.jpg', 'category' => 'SKULLPANDA'],
    2 => ['id' => 2, 'name' => 'HIRONO The Other One', 'price' => 850.00, 'image' => 'img/HIRONO/HIRONO5.jpg', 'category' => 'HIRONO'],
    3 => ['id' => 3, 'name' => 'CRYBABY Night Series', 'price' => 750.00, 'image' => 'img/CRYBABY/CRYBABY1.jpg', 'category' => 'CRYBABY'],
    4 => ['id' => 4, 'name' => 'MOLLY Space Series', 'price' => 790.00, 'image' => 'img/MOLLY/MOLLY2.jpg', 'category' => 'MOLLY'],
    5 => ['id' => 5, 'name' => 'MOLLY Carb-Lover Series Figures', 'price' => 380.00, 'image' => 'img/MOLLY/MOLLY3.jpg', 'category' => 'MOLLY'],
];

// ถ้าไม่มีตะกร้าสินค้า ให้สร้างอาร์เรย์ว่าง
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// จัดการการอัปเดตจำนวนสินค้า
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    // Redirect to avoid form resubmission
    header('Location: cart.php');
    exit;
}

// ลบสินค้าออกจากตะกร้า
if (isset($_GET['remove']) && isset($_SESSION['cart'][$_GET['remove']])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    // Redirect to avoid removal on refresh
    header('Location: cart.php');
    exit;
}

// ล้างตะกร้าสินค้าทั้งหมด
if (isset($_GET['clear_cart'])) {
    $_SESSION['cart'] = [];
    // Redirect to avoid clearing on refresh
    header('Location: cart.php');
    exit;
}

// คำนวณจำนวนสินค้าในตะกร้า
if (isset($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}

// คำนวณราคารวม
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    if (isset($products[$product_id])) {
        $total_price += $products[$product_id]['price'] * $quantity;
    }
}

// คำนวณส่วนลด (ถ้าซื้อครบ 1,500 บาท ลด 10%)
$discount = 0;
$discount_percentage = 0;
if ($total_price >= 1500) {
    $discount_percentage = 10;
    $discount = $total_price * 0.1;
}

// คำนวณราคาสุทธิหลังหักส่วนลด
$net_price = $total_price - $discount;

// ค่าจัดส่ง (สมมติว่าส่งฟรีเมื่อซื้อครบ 1,000 บาท)
$shipping_fee = ($net_price >= 1000) ? 0 : 50;

// ราคารวมสุทธิ
$grand_total = $net_price + $shipping_fee;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - AAA MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>

    <!-- แถบเมนูนำทาง -->
    <?php include 'includes/navbar.php'; ?>

    <!-- ส่วนหัวของหน้า -->
    <div class="page-header">
        <div class="container">
            <h2><i class="fas fa-shopping-cart me-2"></i> ตะกร้าสินค้า</h2>
            <p class="lead">ตรวจสอบและแก้ไขรายการสินค้าของคุณก่อนดำเนินการต่อ</p>
        </div>
    </div>

    <div class="container mb-5">
        <?php if (empty($_SESSION['cart'])): ?>
            <!-- กรณีตะกร้าว่าง -->
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>ตะกร้าสินค้าของคุณว่างเปล่า</h3>
                <p>ยังไม่มีสินค้าในตะกร้า กรุณาเลือกสินค้าที่ต้องการ</p>
                <a href="products.php" class="btn btn-primary mt-3">
                    <i class="fas fa-boxes me-2"></i>เลือกซื้อสินค้า
                </a>
            </div>
        <?php else: ?>
            <!-- กรณีมีสินค้าในตะกร้า -->
            <form action="cart.php" method="POST">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>รายการสินค้า (<?= $cart_count ?> ชิ้น)</h3>
                            <a href="cart.php?clear_cart=1" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-1"></i> ล้างตะกร้า
                            </a>
                        </div>

                        <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
                            <?php if (isset($products[$product_id])): ?>
                                <div class="cart-item p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 col-3">
                                            <img src="<?= $products[$product_id]['image'] ?>" alt="<?= $products[$product_id]['name'] ?>" class="cart-item-img">
                                        </div>
                                        <div class="col-md-5 col-9">
                                            <span class="category-badge"><?= $products[$product_id]['category'] ?></span>
                                            <h5><?= $products[$product_id]['name'] ?></h5>
                                            <p class="product-price">฿<?= number_format($products[$product_id]['price'], 2) ?></p>
                                        </div>
                                        <div class="col-md-3 col-6 mt-3 mt-md-0">
                                            <div class="d-flex align-items-center">
                                                <button type="button" class="btn btn-sm btn-outline-secondary me-2" onclick="decrementQuantity(<?= $product_id ?>)">-</button>
                                                <input type="number" name="quantity[<?= $product_id ?>]" id="quantity-<?= $product_id ?>" value="<?= $quantity ?>" min="1" class="form-control quantity-input">
                                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="incrementQuantity(<?= $product_id ?>)">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6 text-end mt-3 mt-md-0">
                                            <p class="fw-bold">฿<?= number_format($products[$product_id]['price'] * $quantity, 2) ?></p>
                                            <a href="cart.php?remove=<?= $product_id ?>" class="btn-remove">
                                                <i class="fas fa-trash-alt"></i> ลบ
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <button type="submit" name="update_cart" class="btn btn-update mt-3">
                            <i class="fas fa-sync-alt me-1"></i> อัปเดตตะกร้า
                        </button>
                        
                        <a href="products.php" class="continue-shopping">
                            <i class="fas fa-long-arrow-alt-left me-1"></i> เลือกซื้อสินค้าเพิ่มเติม
                        </a>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="summary-card">
                            <h4 class="mb-4">สรุปคำสั่งซื้อ</h4>
                            
                            <div class="summary-row">
                                <span>ราคารวม:</span>
                                <span>฿<?= number_format($total_price, 2) ?></span>
                            </div>
                            
                            <?php if ($discount > 0): ?>
                            <div class="summary-row">
                                <span>ส่วนลด: <span class="discount-badge"><?= $discount_percentage ?>%</span></span>
                                <span>-฿<?= number_format($discount, 2) ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="summary-row">
                                <span>ค่าจัดส่ง:</span>
                                <span><?= $shipping_fee > 0 ? '฿'.number_format($shipping_fee, 2) : 'ฟรี' ?></span>
                            </div>
                            
                            <div class="summary-row total">
                                <span>ราคาสุทธิ:</span>
                                <span>฿<?= number_format($grand_total, 2) ?></span>
                            </div>
                            
                            <?php if ($total_price < 1500): ?>
                            <div class="alert alert-info mt-3" role="alert">
                                <i class="fas fa-info-circle me-1"></i> ซื้อเพิ่มอีก ฿<?= number_format(1500 - $total_price, 2) ?> รับส่วนลด 10%
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($net_price < 1000 && $shipping_fee > 0): ?>
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle me-1"></i> ซื้อเพิ่มอีก ฿<?= number_format(1000 - $net_price, 2) ?> รับส่งฟรี
                            </div>
                            <?php endif; ?>
                            
                            <a href="checkout.php" class="btn btn-checkout mt-3">
                                <i class="fas fa-credit-card me-2"></i> ดำเนินการชำระเงิน
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>

   <!-- Footer -->
   <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript functions for incrementing and decrementing quantities
        function incrementQuantity(productId) {
            const input = document.getElementById(`quantity-${productId}`);
            input.value = parseInt(input.value) + 1;
        }
        
        function decrementQuantity(productId) {
            const input = document.getElementById(`quantity-${productId}`);
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
</body>
</html>