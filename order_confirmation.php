<?php
include("include.php");
session_start();

// ตรวจสอบว่ามีข้อมูลการสั่งซื้อหรือไม่
// ตรวจสอบว่ามีข้อมูลการสั่งซื้อหรือไม่
if (empty($_SESSION['order'])) {
    die("order_confirmation"); // แสดงข้อความและหยุดการทำงาน
}

// ข้อมูลการสั่งซื้อจาก session
$order = $_SESSION['order'];

// ตัวแปรสำหรับการเชื่อมต่อฐานข้อมูลหรือการจัดการอื่นๆ
$cart_count = 0; // กำหนดจำนวนสินค้าที่อยู่ในตะกร้า

// สมมติข้อมูลสินค้าจากฐานข้อมูล (ในระบบจริงควรดึงจากฐานข้อมูล)
$products = [
    1 => ['id' => 1, 'name' => 'Skullpanda City of Night Series', 'price' => 380.00, 'image' => 'img/SKULLPANDA/SKULLPANDA1.jpg', 'category' => 'SKULLPANDA'],
    2 => ['id' => 2, 'name' => 'HIRONO The Other One', 'price' => 850.00, 'image' => 'img/HIRONO/HIRONO5.jpg', 'category' => 'HIRONO'],
    3 => ['id' => 3, 'name' => 'CRYBABY Night Series', 'price' => 750.00, 'image' => 'img/CRYBABY/CRYBABY1.jpg', 'category' => 'CRYBABY'],
    4 => ['id' => 4, 'name' => 'MOLLY Space Series', 'price' => 790.00, 'image' => 'img/MOLLY/MOLLY2.jpg', 'category' => 'MOLLY'],
    5 => ['id' => 5, 'name' => 'MOLLY Carb-Lover Series Figures', 'price' => 380.00, 'image' => 'img/MOLLY/MOLLY3.jpg', 'category' => 'MOLLY'],
];

// การจัดการข้อมูลการชำระเงิน
$payment_method_text = '';
$payment_instructions = '';

switch ($order['payment_method']) {
    case 'bank_transfer':
        $payment_method_text = 'โอนเงินผ่านธนาคาร';
        $payment_instructions = '
            <div class="bank-accounts mt-3">
                <p class="mb-2">โอนเงินมาที่บัญชี:</p>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <img src="img/bank-kbank.png" alt="KBANK" height="30" class="me-2">
                            ธนาคารกสิกรไทย
                        </div>
                        <div>
                            <strong>123-4-56789-0</strong>
                            <div class="small text-muted">ชื่อบัญชี: บริษัท AAA MART จำกัด</div>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <img src="img/bank-scb.png" alt="SCB" height="30" class="me-2">
                            ธนาคารไทยพาณิชย์
                        </div>
                        <div>
                            <strong>987-6-54321-0</strong>
                            <div class="small text-muted">ชื่อบัญชี: บริษัท AAA MART จำกัด</div>
                        </div>
                    </li>
                </ul>
                <div class="mt-3 alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> กรุณาแจ้งการชำระเงินพร้อมแนบหลักฐานการโอนเงินภายใน 24 ชั่วโมง ผ่านทางอีเมล: payment@aaamart.com หรือ <a href="payment_confirmation.php" class="alert-link">แจ้งชำระเงิน</a>
                </div>
            </div>
        ';
        break;
    case 'credit_card':
        $payment_method_text = 'บัตรเครดิต/เดบิต';
        $payment_instructions = '
            <div class="mt-3 alert alert-success">
                <i class="fas fa-check-circle me-2"></i> การชำระเงินด้วยบัตรเครดิต/เดบิตสำเร็จแล้ว
            </div>
        ';
        break;
    case 'prompt_pay':
        $payment_method_text = 'พร้อมเพย์ QR Code';
        $payment_instructions = '
            <div class="mt-3 text-center">
                <p>สแกน QR Code เพื่อชำระเงิน</p>
                <img src="img/qr-payment.png" alt="QR Code Payment" class="img-fluid mb-3" style="max-width: 200px;">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> กรุณาชำระเงินภายใน 24 ชั่วโมง หากเกินกำหนดคำสั่งซื้อจะถูกยกเลิกอัตโนมัติ
                </div>
            </div>
        ';
        break;
    case 'cash_on_delivery':
        $payment_method_text = 'เก็บเงินปลายทาง';
        $payment_instructions = '
            <div class="mt-3 alert alert-info">
                <i class="fas fa-info-circle me-2"></i> ชำระเงินเมื่อได้รับสินค้า จำนวนเงิน: ฿' . number_format($order['grand_total'], 2) . ' (รวมค่าธรรมเนียมเก็บเงินปลายทาง 20 บาท)
            </div>
        ';
        break;
    default:
        $payment_method_text = 'ไม่ระบุ';
        break;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการสั่งซื้อ - AAA MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/order_confirmation.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- แถบเมนูนำทาง -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-store me-2"></i>AAA MART
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <div class="hamburger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> หน้าแรก</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-boxes me-1"></i> สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link" href="promotion.php"><i class="fas fa-tags me-1"></i> โปรโมชั่น</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-envelope me-1"></i> ติดต่อเรา</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <?php if(isset($_SESSION['user'])): ?>
                                <i class="fas fa-sign-out-alt me-1"></i> ออกจากระบบ
                            <?php else: ?>
                                <i class="fas fa-sign-in-alt me-1"></i> เข้าสู่ระบบ
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart me-1"></i> ตะกร้าสินค้า 
                            <span class="badge bg-danger"><?= $cart_count ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ส่วนหัวของหน้า -->
    <div class="page-header">
        <div class="container">
            <h2><i class="fas fa-check-circle me-2"></i> ยืนยันการสั่งซื้อ</h2>
            <p class="lead">คำสั่งซื้อของคุณได้รับการยืนยันเรียบร้อยแล้ว</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="confirmation-card">
            <div class="order-success">
                <i class="fas fa-check-circle success-icon"></i>
                <h3>ขอบคุณสำหรับการสั่งซื้อ!</h3>
                <p>คำสั่งซื้อของคุณได้รับการยืนยันเรียบร้อยแล้ว เราจะจัดส่งอีเมลยืนยันพร้อมรายละเอียดการสั่งซื้อไปยัง <?= $order['email'] ?></p>
                <p class="mt-3">รหัสคำสั่งซื้อของคุณ:</p>
                <div class="order-id">
                    <?= $order['order_id'] ?>
                </div>
                <p class="text-muted mt-3">วันที่สั่งซื้อ: <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="confirmation-section">
                        <h5 class="section-title"><i class="fas fa-user me-2"></i>ข้อมูลลูกค้า</h5>
                        <div class="detail-row">
                            <span class="detail-label">ชื่อ-นามสกุล:</span>
                            <span><?= $order['fullname'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">อีเมล:</span>
                            <span><?= $order['email'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">เบอร์โทรศัพท์:</span>
                            <span><?= $order['phone'] ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="confirmation-section">
                        <h5 class="section-title"><i class="fas fa-truck me-2"></i>ข้อมูลจัดส่ง</h5>
                        <div class="detail-row">
                            <span class="detail-label">ที่อยู่:</span>
                            <span><?= $order['address'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">แขวง/ตำบล:</span>
                            <span><?= $order['district'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">เขต/อำเภอ และจังหวัด:</span>
                            <span><?= $order['province'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">รหัสไปรษณีย์:</span>
                            <span><?= $order['postal_code'] ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="confirmation-section mt-4">
                <h5 class="section-title"><i class="fas fa-shopping-bag me-2"></i>รายการสินค้า</h5>
                <div class="product-list">
                    <?php foreach ($order['items'] as $product_id => $quantity): 
                        if (isset($products[$product_id])): ?>
                        <div class="product-item">
                            <img src="<?= $products[$product_id]['image'] ?>" alt="<?= $products[$product_id]['name'] ?>" class="product-img">
                            <div class="product-info">
                                <div class="product-name"><?= $products[$product_id]['name'] ?></div>
                                <div class="d-flex justify-content-between">
                                    <span>จำนวน: <?= $quantity ?> ชิ้น</span>
                                    <span>฿<?= number_format($products[$product_id]['price'] * $quantity, 2) ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif;
                    endforeach; ?>
                </div>
                
                <div class="price-summary">
                    <div class="summary-row">
                        <span>ราคารวม:</span>
                        <span>฿<?= number_format($order['total_price'], 2) ?></span>
                    </div>
                    
                    <?php if ($order['discount'] > 0): ?>
                    <div class="summary-row">
                        <span>ส่วนลด: <span class="discount-badge">10%</span></span>
                        <span>-฿<?= number_format($order['discount'], 2) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="summary-row">
                        <span>ค่าจัดส่ง:</span>
                        <span><?= $order['shipping_fee'] > 0 ? '฿'.number_format($order['shipping_fee'], 2) : 'ฟรี' ?></span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>ราคาสุทธิ:</span>
                        <span>฿<?= number_format($order['grand_total'], 2) ?></span>
                    </div>
                </div>
            </div>

            <div class="confirmation-section mt-4">
                <h5 class="section-title"><i class="fas fa-wallet me-2"></i>ข้อมูลการชำระเงิน</h5>
                <div class="detail-row">
                    <span class="detail-label">วิธีการชำระเงิน:</span>
                    <span><?= $payment_method_text ?></span>
                </div>
                <?= $payment_instructions ?>
            </div>

            <div class="steps-container">
                <h5 class="section-title"><i class="fas fa-clipboard-list me-2"></i>ขั้นตอนต่อไป</h5>
                <ul class="timeline">
                    <li class="timeline-item">
                        <div class="timeline-badge active">1</div>
                        <div class="timeline-panel">
                            <h5 class="timeline-title">ยืนยันคำสั่งซื้อ</h5>
                            <p class="timeline-date"><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>
                            <p class="mb-0">คำสั่งซื้อของคุณได้รับการยืนยันเรียบร้อยแล้ว</p>
                        </div>
                    </li>
                    
                    <li class="timeline-item">
                        <div class="timeline-badge">2</div>
                        <div class="timeline-panel">
                            <h5 class="timeline-title">รอการชำระเงิน</h5>
                            <p class="timeline-date">รอดำเนินการ</p>
                            <p class="mb-0">กรุณาชำระเงินให้เรียบร้อยเพื่อดำเนินการในขั้นตอนต่อไป</p>
                        </div>
                    </li>
                    
                    <li class="timeline-item">
                        <div class="timeline-badge">3</div>
                        <div class="timeline-panel">
                            <h5 class="timeline-title">กำลังเตรียมจัดส่ง</h5>
                            <p class="timeline-date">รอดำเนินการ</p>
                            <p class="mb-0">เราจะเตรียมสินค้าและบรรจุเพื่อจัดส่งให้กับคุณ</p>
                        </div>
                    </li>
                    
                    <li class="timeline-item">
                        <div class="timeline-badge">4</div>
                        <div class="timeline-panel">
                            <h5 class="timeline-title">จัดส่งสินค้า</h5>
                            <p class="timeline-date">รอดำเนินการ</p>
                            <p class="mb-0">สินค้าอยู่ระหว่างการจัดส่ง คุณสามารถติดตามสถานะได้จากหน้าติดตามคำสั่งซื้อ</p>
                        </div>
                    </li>
                    
                    <li class="timeline-item">
                        <div class="timeline-badge">5</div>
                        <div class="timeline-panel">
                            <h5 class="timeline-title">จัดส่งสำเร็จ</h5>
                            <p class="timeline-date">รอดำเนินการ</p>
                            <p class="mb-0">สินค้าถูกจัดส่งถึงคุณเรียบร้อยแล้ว ขอบคุณที่ไว้วางใจเลือกซื้อสินค้ากับเรา</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="index.php" class="btn btn-continue-shopping">
                    <i class="fas fa-shopping-cart me-2"></i> เลือกซื้อสินค้าต่อ
                </a>
                <a href="track_order.php?id=<?= $order['order_id'] ?>" class="btn btn-track-order">
                    <i class="fas fa-truck me-2"></i> ติดตามคำสั่งซื้อ
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
<footer class="bg-dark text-white text-center p-5 mt-8">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-start">
                <h5>AAA MART</h5>
                <p class="small">ร้านขายตัวการ์ตูนสะสมคุณภาพ</p>
            </div>
            <div class="col-md-6 text-end">
                <p class="small mb-0">&copy; 2025 AAA MART</p>
                <p class="small">สงวนลิขสิทธิ์ทั้งหมด</p>
            </div>
        </div>
    </div>
</footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body> 