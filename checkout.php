<?php
include("include.php");
session_start();

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

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

// รับค่าจากฟอร์ม
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบข้อมูลที่ส่งมา
    if (empty($_POST['fullname'])) {
        $errors[] = 'กรุณากรอกชื่อ-นามสกุล';
    }
    
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'กรุณากรอกอีเมลให้ถูกต้อง';
    }
    
    if (empty($_POST['phone']) || !preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
        $errors[] = 'กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (10 หลัก)';
    }
    
    if (empty($_POST['address'])) {
        $errors[] = 'กรุณากรอกที่อยู่';
    }
    
    if (empty($_POST['payment_method'])) {
        $errors[] = 'กรุณาเลือกวิธีการชำระเงิน';
    }
    
    // ถ้าไม่มีข้อผิดพลาด
    if (empty($errors)) {
        // บันทึกข้อมูลการสั่งซื้อ (ในระบบจริงควรบันทึกลงฐานข้อมูล)
        // สร้างรหัสคำสั่งซื้อ
        $order_id = 'ORDER' . date('YmdHis') . rand(100, 999);
        
        // เก็บข้อมูลคำสั่งซื้อใน session (ในระบบจริงควรบันทึกลงฐานข้อมูล)
        $_SESSION['order'] = [
            'order_id' => $order_id,
            'fullname' => $_POST['fullname'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'district' => $_POST['district'],
            'province' => $_POST['province'],
            'postal_code' => $_POST['postal_code'],
            'payment_method' => $_POST['payment_method'],
            'items' => $_SESSION['cart'],
            'total_price' => $total_price,
            'discount' => $discount,
            'shipping_fee' => $shipping_fee,
            'grand_total' => $grand_total,
            'order_date' => date('Y-m-d H:i:s')
        ];
        
        // ล้างตะกร้าสินค้า
        $_SESSION['cart'] = [];
        
        // Redirect ไปยังหน้ายืนยันการสั่งซื้อ
        header('Location: order_confirmation.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน - AAA MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/checkout.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- แถบเมนูนำทาง -->
    <?php include 'includes/navbar.php'; ?>

    <!-- ส่วนหัวของหน้า -->
    <div class="page-header">
        <div class="container">
            <h2><i class="fas fa-credit-card me-2"></i> ชำระเงิน</h2>
            <p class="lead">กรอกข้อมูลการจัดส่งและชำระเงินเพื่อดำเนินการสั่งซื้อ</p>
        </div>
    </div>

    <div class="container mb-5">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="checkout.php" method="POST">
            <div class="row">
                <div class="col-lg-8">
                    <!-- ข้อมูลลูกค้า -->
                    <div class="checkout-section">
                        <h4 class="section-title">
                            <span class="section-number">1</span>
                            <i class="fas fa-user step-icon"></i>ข้อมูลลูกค้า
                        </h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="fullname" class="form-label required-field">ชื่อ-นามสกุล</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="กรอกชื่อ-นามสกุล" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label required-field">อีเมล</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
                                <div class="form-text">เราจะส่งรายละเอียดการสั่งซื้อไปยังอีเมลของคุณ</div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label required-field">เบอร์โทรศัพท์</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="0812345678" required pattern="[0-9]{10}">
                            </div>
                        </div>
                    </div>

                    <!-- ข้อมูลการจัดส่ง -->
                    <div class="checkout-section">
                        <h4 class="section-title">
                            <span class="section-number">2</span>
                            <i class="fas fa-truck step-icon"></i>ที่อยู่จัดส่ง
                        </h4>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="address" class="form-label required-field">ที่อยู่</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="บ้านเลขที่, หมู่บ้าน, ถนน, ซอย" required></textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="district" class="form-label required-field">แขวง/ตำบล</label>
                                <input type="text" class="form-control" id="district" name="district" required>
                            </div>
                            <div class="col-md-4">
                                <label for="province" class="form-label required-field">เขต/อำเภอ และจังหวัด</label>
                                <input type="text" class="form-control" id="province" name="province" required>
                            </div>
                            <div class="col-md-4">
                                <label for="postal_code" class="form-label required-field">รหัสไปรษณีย์</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" required pattern="[0-9]{5}">
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="save_address">
                                    <label class="form-check-label" for="save_address">
                                        บันทึกที่อยู่นี้สำหรับการสั่งซื้อครั้งต่อไป
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- วิธีการชำระเงิน -->
                    <div class="checkout-section">
                        <h4 class="section-title">
                            <span class="section-number">3</span>
                            <i class="fas fa-wallet step-icon"></i>วิธีการชำระเงิน
                        </h4>
                        <div class="payment-methods">
                            <div class="payment-method selected" onclick="selectPaymentMethod('bank_transfer')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" checked>
                                    <label class="form-check-label d-flex align-items-center" for="bank_transfer">
                                        <i class="fas fa-university payment-icon"></i>
                                        <div>
                                            <strong>โอนเงินผ่านธนาคาร</strong>
                                            <p class="mb-0 text-muted">โอนเงินผ่านบัญชีธนาคารหรือ Mobile Banking</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-method" onclick="selectPaymentMethod('credit_card')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card">
                                    <label class="form-check-label d-flex align-items-center" for="credit_card">
                                        <i class="fas fa-credit-card payment-icon"></i>
                                        <div>
                                            <strong>บัตรเครดิต/เดบิต</strong>
                                            <p class="mb-0 text-muted">ชำระด้วยบัตรเครดิตหรือเดบิตที่รองรับ</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-method" onclick="selectPaymentMethod('prompt_pay')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="prompt_pay" value="prompt_pay">
                                    <label class="form-check-label d-flex align-items-center" for="prompt_pay">
                                        <i class="fas fa-qrcode payment-icon"></i>
                                        <div>
                                            <strong>พร้อมเพย์ QR Code</strong>
                                            <p class="mb-0 text-muted">สแกน QR Code ผ่านแอปธนาคารหรือแอปพลิเคชันอื่นๆ</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-method" onclick="selectPaymentMethod('cash_on_delivery')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery">
                                    <label class="form-check-label d-flex align-items-center" for="cash_on_delivery">
                                        <i class="fas fa-money-bill-wave payment-icon"></i>
                                        <div>
                                            <strong>เก็บเงินปลายทาง</strong>
                                            <p class="mb-0 text-muted">ชำระเงินเมื่อได้รับสินค้า (มีค่าธรรมเนียมเพิ่ม 20 บาท)</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="summary-card">
                        <h4 class="mb-4">สรุปคำสั่งซื้อ</h4>
                        
                        <div class="product-summary">
                            <h5>รายการสินค้า</h5>
                            
                            <?php 
                            $count = 0;
                            foreach ($_SESSION['cart'] as $product_id => $quantity): 
                                if (isset($products[$product_id])):
                                    $count++;
                                    // แสดงเฉพาะ 3 รายการแรก
                                    if ($count <= 3):
                            ?>
                            <div class="product-item">
                                <img src="<?= $products[$product_id]['image'] ?>" alt="<?= $products[$product_id]['name'] ?>" class="product-img">
                                <div class="product-info">
                                    <div class="product-name"><?= $products[$product_id]['name'] ?></div>
                                    <div class="d-flex justify-content-between">
                                        <span><?= $quantity ?> x ฿<?= number_format($products[$product_id]['price'], 2) ?></span>
                                        <span>฿<?= number_format($products[$product_id]['price'] * $quantity, 2) ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                    endif;
                                endif;
                            endforeach; 
                            
                            // ถ้ามีสินค้ามากกว่า 3 ชิ้น
                            if (count($_SESSION['cart']) > 3):
                            ?>
                            <div class="text-center mt-2">
                                <a href="cart.php" class="text-decoration-none">ดูสินค้าทั้งหมด (<?= count($_SESSION['cart']) ?> รายการ)</a>
                            </div>
                            <?php endif; ?>
                        </div>
                        
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
                        
                        <button type="submit" class="btn btn-place-order mt-4">
                            <i class="fas fa-check-circle me-2"></i> ยืนยันการสั่งซื้อ
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="cart.php" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i> กลับไปยังตะกร้าสินค้า
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPaymentMethod(method) {
            document.querySelectorAll('.payment-method').forEach(function(element) {
                element.classList.remove('selected');
            });
            document.getElementById(method).checked = true;
            document.querySelector('.payment-method[onclick="selectPaymentMethod(\'' + method + '\')"]').classList.add('selected');
        }
    </script>
</body>
</html>