<?php
include("include.php");
session_start();

// ตัวแปรสำหรับการเชื่อมต่อฐานข้อมูลหรือการจัดการอื่นๆ
$cart_count = 0; // กำหนดจำนวนสินค้าที่อยู่ในตะกร้า
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}

// ตัวอย่างข้อมูลสินค้า (ควรดึงจากฐานข้อมูล)
$products = [
    1 => [
        'name' => 'Skullpanda City of Night',
        'price' => 850.00,
        'image' => 'img/SKULLPANDA/SKULLPANDA1.jpg',
        'description' => 'Skullpanda City of Night เป็นตัวละครสะสมที่ได้รับความนิยมจากคอลเลคชั่น City of Night มาพร้อมกับดีเทลที่สวยงามและสีสันที่สดใส',
        'category' => 'SKULLPANDA'
    ],
    2 => [
        'name' => 'HIRONO The Other One',
        'price' => 850.00,
        'image' => 'img/HIRONO/HIRONO5.jpg',
        'description' => 'HIRONO The Other One เป็นตัวละครสะสมที่โดดเด่นด้วยดีไซน์ที่ล้ำสมัยและความหมายที่ลึกซึ้ง',
        'category' => 'HIRONO'
    ],
    3 => [
        'name' => 'CRYBABY Night Series',
        'price' => 750.00,
        'image' => 'img/CRYBABY/CRYBABY1.jpg',
        'description' => 'CRYBABY Night Series เป็นตัวละครสะสมที่เหมาะสำหรับผู้ที่ชื่นชอบความน่ารักและความลึกลับ',
        'category' => 'CRYBABY'
    ],
    4 => [
        'name' => 'MOLLY Space Series',
        'price' => 790.00,
        'image' => 'img/MOLLY/MOLLY2.jpg',
        'description' => 'MOLLY Space Series เป็นตัวละครสะสมที่นำเสนอธีมอวกาศที่สนุกสนานและน่าตื่นเต้น',
        'category' => 'MOLLY'
    ]
];

// ดึงข้อมูลสินค้าจาก ID ที่ส่งมา
$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = isset($products[$product_id]) ? $products[$product_id] : null;

if (!$product) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAA MART - <?= htmlspecialchars($product['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/product_details.css">

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

    <!-- รายละเอียดสินค้า -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="product-image">
                    <img src="<?= htmlspecialchars($product['image']) ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded">
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-details">
                    <h1><?= htmlspecialchars($product['name']) ?></h1>
                    <p class="product-price">฿<?= number_format($product['price'], 2) ?></p>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <div class="d-flex gap-3 mt-4">
                        <form method="POST" action="add_to_cart.php">
                            <button type="submit" name="product_id" value="<?= $product_id ?>"
                                class="btn btn-success btn-lg">
                                <i class="fas fa-cart-plus me-2"></i>เพิ่มลงตะกร้า
                            </button>
                        </form>
                        <a href="products.php?category=<?= htmlspecialchars($product['category']) ?>"
                            class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>กลับไปที่หมวดหมู่
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-start">
                    <h5>AAA MART</h5>
                    <p class="small">ร้านขายตัวการ์ตูนสะสมคุณภาพ</p>
                </div>
                <div class="col-md-4">
                    <h5>ลิงก์ด่วน</h5>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="about.php" class="text-white small">เกี่ยวกับเรา</a>
                        <a href="faq.php" class="text-white small">คำถามที่พบบ่อย</a>
                        <a href="policy.php" class="text-white small">นโยบาย</a>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <p class="small mb-0">&copy; 2025 AAA MART</p>
                    <p class="small">สงวนลิขสิทธิ์ทั้งหมด</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>