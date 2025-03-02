<?php
include("include.php");
session_start();

// ตัวแปรสำหรับการเชื่อมต่อฐานข้อมูลหรือการจัดการอื่นๆ
$cart_count = 0; // กำหนดจำนวนสินค้าที่อยู่ในตะกร้า

// ตรวจสอบว่ามีการส่งรหัสคำสั่งซื้อมาหรือไม่
$order_id = '';
$tracking_result = false;
$order = null;
$current_status = 1; // ค่าเริ่มต้น (1 = ยืนยันคำสั่งซื้อ)

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $order_id = $_GET['id'];
    $tracking_result = true;

    // ในระบบจริงควรดึงข้อมูลจากฐานข้อมูล
    // สมมติข้อมูลคำสั่งซื้อสำหรับตัวอย่าง
    if (isset($_SESSION['order']) && $_SESSION['order']['order_id'] == $order_id) {
        $order = $_SESSION['order'];
        $current_status = 2; // สมมติสถานะ (2 = รอการชำระเงิน)
    } else {
        // สมมติข้อมูลสำหรับการทดสอบเท่านั้น
        $order = [
            'order_id' => $order_id,
            'order_date' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'fullname' => 'ทดสอบ ระบบติดตาม',
            'email' => 'test@example.com',
            'phone' => '09x-xxx-xxxx',
            'address' => '123 ถนนตัวอย่าง',
            'district' => 'แขวงตัวอย่าง',
            'province' => 'เขตตัวอย่าง กรุงเทพมหานคร',
            'postal_code' => '10xxx',
            'payment_method' => 'bank_transfer',
            'shipping_method' => 'ไปรษณีย์ไทย EMS',
            'tracking_number' => 'TH1234567890',
            'status' => 'รอการชำระเงิน',
            'items' => [
                1 => 1,
                3 => 2
            ],
            'total_price' => 1850.00,
            'discount' => 185.00,
            'shipping_fee' => 50.00,
            'grand_total' => 1715.00,
            'status_updates' => [
                [
                    'status' => 'ยืนยันคำสั่งซื้อ',
                    'date' => date('Y-m-d H:i:s', strtotime('-1 day')),
                    'description' => 'คำสั่งซื้อของคุณได้รับการยืนยันเรียบร้อยแล้ว'
                ],
                [
                    'status' => 'รอการชำระเงิน',
                    'date' => date('Y-m-d H:i:s', strtotime('-1 day +1 hour')),
                    'description' => 'กรุณาชำระเงินเพื่อดำเนินการในขั้นตอนต่อไป'
                ]
            ]
        ];

        // สุ่มสถานะสำหรับการทดสอบ
        $current_status = rand(1, 4);
    }
}

// สมมติข้อมูลสินค้า
$products = [
    1 => ['id' => 1, 'name' => 'Skullpanda City of Night Series', 'price' => 380.00, 'image' => 'img/SKULLPANDA/SKULLPANDA1.jpg', 'category' => 'SKULLPANDA'],
    2 => ['id' => 2, 'name' => 'HIRONO The Other One', 'price' => 850.00, 'image' => 'img/HIRONO/HIRONO5.jpg', 'category' => 'HIRONO'],
    3 => ['id' => 3, 'name' => 'CRYBABY Night Series', 'price' => 750.00, 'image' => 'img/CRYBABY/CRYBABY1.jpg', 'category' => 'CRYBABY'],
    4 => ['id' => 4, 'name' => 'MOLLY Space Series', 'price' => 790.00, 'image' => 'img/MOLLY/MOLLY2.jpg', 'category' => 'MOLLY'],
    5 => ['id' => 5, 'name' => 'MOLLY Carb-Lover Series Figures', 'price' => 380.00, 'image' => 'img/MOLLY/MOLLY3.jpg', 'category' => 'MOLLY'],
];

// กำหนดขั้นตอนการจัดส่ง
$steps = [
    1 => [
        'title' => 'ยืนยันคำสั่งซื้อ',
        'description' => 'คำสั่งซื้อของคุณได้รับการยืนยันเรียบร้อยแล้ว'
    ],
    2 => [
        'title' => 'รอการชำระเงิน',
        'description' => 'กรุณาชำระเงินให้เรียบร้อยเพื่อดำเนินการในขั้นตอนต่อไป'
    ],
    3 => [
        'title' => 'กำลังเตรียมจัดส่ง',
        'description' => 'เราจะเตรียมสินค้าและบรรจุเพื่อจัดส่งให้กับคุณ'
    ],
    4 => [
        'title' => 'จัดส่งสินค้า',
        'description' => 'สินค้าอยู่ระหว่างการจัดส่ง คุณสามารถติดตามสถานะได้จากหน้าติดตามคำสั่งซื้อ'
    ],
    5 => [
        'title' => 'จัดส่งสำเร็จ',
        'description' => 'สินค้าถูกจัดส่งถึงคุณเรียบร้อยแล้ว ขอบคุณที่ไว้วางใจเลือกซื้อสินค้ากับเรา'
    ]
];

// กำหนดการแสดงผลสถานะการจัดส่ง
$shipping_status = '';
$tracking_info = '';

if ($tracking_result && $order) {
    if ($current_status >= 4) {
        // ถ้าสถานะการจัดส่งแล้ว
        $shipping_status = '<span class="badge bg-primary">กำลังจัดส่ง</span>';
        $tracking_info = '
            <div class="tracking-info mt-4">
                <h6 class="mb-3">ข้อมูลการจัดส่ง:</h6>
                <div class="carrier-info">
                    <p><strong>บริษัทขนส่ง:</strong> ' . $order['shipping_method'] . '</p>
                    <p><strong>หมายเลขพัสดุ:</strong> <a href="https://track.thailandpost.co.th/?trackNumber=' . $order['tracking_number'] . '" target="_blank" class="tracking-link">' . $order['tracking_number'] . ' <i class="fas fa-external-link-alt small"></i></a></p>
                </div>
                ?>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> คุณสามารถตรวจสอบสถานะพัสดุได้โดยตรงจากเว็บไซต์ของผู้ให้บริการขนส่ง
                </div>
            </div>
        ';
    }
}

// ฟังก์ชันสำหรับตรวจสอบสถานะการติดตาม
function getStatusClass($step, $current_status)
{
    if ($step < $current_status) {
        return 'completed'; // ขั้นตอนที่เสร็จแล้ว
    } elseif ($step == $current_status) {
        return 'active'; // ขั้นตอนปัจจุบัน
    } else {
        return 'pending'; // ขั้นตอนที่ยังไม่ถึง
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตามคำสั่งซื้อ - AAA MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/track_order.css">
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
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>
                            หน้าแรก</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-boxes me-1"></i>
                            สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link" href="promotion.php"><i class="fas fa-tags me-1"></i>
                            โปรโมชั่น</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-envelope me-1"></i>
                            ติดต่อเรา</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <?php if (isset($_SESSION['user'])): ?>
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
            <h2><i class="fas fa-truck me-2"></i> ติดตามคำสั่งซื้อ</h2>
            <p class="lead">ตรวจสอบสถานะคำสั่งซื้อของคุณได้ที่นี่</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="track-order-card">
            <!-- ฟอร์มค้นหาคำสั่งซื้อ -->
            <div class="tracking-form">
                <h5 class="section-title text-center"><i class="fas fa-search me-2"></i>ค้นหาคำสั่งซื้อ</h5>

                <form action="track_order.php" method="get" class="mt-4">
                    <div class="input-group">
                        <input type="text" name="id" class="form-control search-input"
                            placeholder="กรอกรหัสคำสั่งซื้อของคุณ" value="<?= $order_id ?>" required>
                        <button type="submit" class="btn btn-primary search-btn">
                            <i class="fas fa-search me-2"></i> ค้นหา
                        </button>
                    </div>
                    <div class="form-text text-center mt-2">รหัสคำสั่งซื้อจะอยู่ในอีเมลยืนยันการสั่งซื้อของคุณ</div>
                </form>
            </div>

            <?php if (!$tracking_result): ?>
                <!-- แสดงเมื่อยังไม่มีการค้นหา -->
                <div class="empty-state">
                    <i class="fas fa-box empty-icon"></i>
                    <h5>ยังไม่มีข้อมูลคำสั่งซื้อที่แสดง</h5>
                    <p class="text-muted">กรุณากรอกรหัสคำสั่งซื้อเพื่อตรวจสอบสถานะ</p>
                </div>
            <?php elseif ($tracking_result && !$order): ?>
                <!-- แสดงเมื่อไม่พบคำสั่งซื้อ -->
                <div class="alert alert-warning mt-4">
                    <i class="fas fa-exclamation-triangle me-2"></i> ไม่พบข้อมูลคำสั่งซื้อรหัส
                    <strong><?= $order_id ?></strong> กรุณาตรวจสอบรหัสคำสั่งซื้อของคุณอีกครั้ง
                </div>
            <?php else: ?>
                <!-- แสดงผลการติดตาม -->
                <div class="tracking-result">
                    <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>ข้อมูลคำสั่งซื้อ</h5>

                    <div class="order-id-display">
                        <?= $order['order_id'] ?>     <?= $shipping_status ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>วันที่สั่งซื้อ:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?>
                            </p>
                            <p><strong>ชื่อผู้สั่ง:</strong> <?= $order['fullname'] ?></p>
                            <p><strong>อีเมล:</strong> <?= $order['email'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>ช่องทางการชำระเงิน:</strong>
                                <?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?></p>
                            <p><strong>ยอดรวมทั้งสิ้น:</strong> ฿<?= number_format($order['grand_total'], 2) ?></p>
                            <p><strong>สถานะปัจจุบัน:</strong> <span
                                    class="fw-bold text-primary"><?= $steps[$current_status]['title'] ?></span></p>
                        </div>
                    </div>

                    <?= $tracking_info ?>

                    <h5 class="section-title mt-4"><i class="fas fa-clipboard-list me-2"></i>ติดตามสถานะคำสั่งซื้อ</h5>

                    <div class="tracking-steps">
                        <?php for ($i = 1; $i <= 5; $i++):
                            $status_class = getStatusClass($i, $current_status);

                            // กำหนดวันที่สำหรับการแสดงผล
                            $step_date = '';
                            $icon_class = '';

                            switch ($i) {
                                case 1:
                                    $step_date = date('d/m/Y H:i', strtotime($order['order_date']));
                                    $icon_class = 'fa-check-circle';
                                    break;
                                case 2:
                                    if ($i <= $current_status) {
                                        $step_date = date('d/m/Y H:i', strtotime($order['order_date'] . ' +1 hour'));
                                    }
                                    $icon_class = 'fa-wallet';
                                    break;
                                case 3:
                                    if ($i <= $current_status) {
                                        $step_date = date('d/m/Y H:i', strtotime($order['order_date'] . ' +1 day'));
                                    }
                                    $icon_class = 'fa-box';
                                    break;
                                case 4:
                                    if ($i <= $current_status) {
                                        $step_date = date('d/m/Y H:i', strtotime($order['order_date'] . ' +2 days'));
                                    }
                                    $icon_class = 'fa-shipping-fast';
                                    break;
                                case 5:
                                    if ($i <= $current_status) {
                                        $step_date = date('d/m/Y H:i', strtotime($order['order_date'] . ' +4 days'));
                                    }
                                    $icon_class = 'fa-home';
                                    break;
                            }
                            ?>
                            <div class="step <?= $status_class ?>">
                                <div class="step-icon">
                                    <i class="fas <?= $icon_class ?>"></i>
                                </div>
                                <div class="step-content">
                                    <h5 class="step-title"><?= $steps[$i]['title'] ?></h5>
                                    <?php if (!empty($step_date)): ?>
                                        <div class="step-date">
                                            <i class="far fa-clock me-1"></i> <?= $step_date ?>
                                        </div>
                                    <?php endif; ?>
                                    <p class="step-description"><?= $steps[$i]['description'] ?></p>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="order-summary">
                        <h5 class="section-title"><i class="fas fa-shopping-bag me-2"></i>รายการสินค้า</h5>

                        <div class="product-list">
                            <?php
                            $total_items = 0;
                            foreach ($order['items'] as $product_id => $quantity):
                                if (isset($products[$product_id])):
                                    $total_items += $quantity;
                                    ?>
                                    <div class="product-item">
                                        <img src="<?= $products[$product_id]['image'] ?>"
                                            alt="<?= $products[$product_id]['name'] ?>" class="product-img">
                                        <div class="product-info">
                                            <div class="product-name"><?= $products[$product_id]['name'] ?></div>
                                            <div class="d-flex justify-content-between">
                                                <span>จำนวน: <?= $quantity ?> ชิ้น</span>
                                                <span>฿<?= number_format($products[$product_id]['price'] * $quantity, 2) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                endif;
                            endforeach;
                            ?>
                        </div>

                        <div class="price-summary mt-3 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1">จำนวนสินค้าทั้งหมด:</p>
                                    <p class="mb-1">ราคารวม:</p>
                                    <?php if ($order['discount'] > 0): ?>
                                        <p class="mb-1">ส่วนลด:</p>
                                    <?php endif; ?>
                                    <p class="mb-1">ค่าจัดส่ง:</p>
                                    <p class="fw-bold mb-0">ราคาสุทธิ:</p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-1"><?= $total_items ?> ชิ้น</p>
                                    <p class="mb-1">฿<?= number_format($order['total_price'], 2) ?></p>
                                    <?php if ($order['discount'] > 0): ?>
                                        <p class="mb-1">-฿<?= number_format($order['discount'], 2) ?></p>
                                    <?php endif; ?>
                                    <p class="mb-1">฿<?= number_format($order['shipping_fee'], 2) ?></p>
                                    <p class="fw-bold mb-0">฿<?= number_format($order['grand_total'], 2) ?></p>
                                </div>
                            </div>
                            <div class="track-btns mt-4">
                                <a href="products.php" class="btn btn-outline-primary track-btn">
                                    <i class="fas fa-arrow-left me-2"></i>กลับไปที่หน้าสินค้า
                                </a>
                                <a href="cart.php" class="btn btn-primary track-btn">
                                    <i class="fas fa-shopping-cart me-2"></i>ดูตะกร้าสินค้า
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>