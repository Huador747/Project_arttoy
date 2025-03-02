<?php
include("include.php");
session_start();

// ตัวแปรสำหรับการเชื่อมต่อฐานข้อมูลหรือการจัดการอื่นๆ
$cart_count = 0; // กำหนดจำนวนสินค้าที่อยู่ในตะกร้า
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAA MART - สินค้าทั้งหมด</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
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
                    <li class="nav-item"><a class="nav-link active" href="products.php"><i class="fas fa-boxes me-1"></i>
                            สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link active" href="promotion.php"><i class="fas fa-tags me-1"></i>
                            โปรโมชั่น</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-envelope me-1"></i>
                            ติดต่อเรา</a></li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])): ?>
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt me-1"></i> ออกจากระบบ
                            </a>
                        <?php else: ?>
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt me-1"></i> เข้าสู่ระบบ
                            </a>
                        <?php endif; ?>
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

    <!-- แสดงข้อความแจ้งเตือน (Alert Messages) -->
    <?php if (isset($_GET['message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= ($_GET['status'] ?? 'info') ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="hero-heading">สินค้าทั้งหมด</h1>
                    <p class="lead mb-4">ค้นหาสินค้าที่คุณชื่นชอบจากคอลเลคชั่นของเรา</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ส่วนค้นหาสินค้า -->
    <div class="container mt-5">
        <div class="card search-card p-4">
            <h3 class="section-heading">ค้นหาสินค้า</h3>
            <form action="search.php" method="GET">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label><i class="fas fa-search me-2"></i>ค้นหาสินค้า</label>
                        <input type="text" name="query" class="form-control form-control-lg"
                            placeholder="พิมพ์ชื่อสินค้า" required>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary btn-lg mt-3">
                    <i class="fas fa-search me-2"></i>ค้นหา
                </button>
            </form>
        </div>
    </div>

    <!-- ส่วนของ Filter Sidebar และสินค้า -->
    <div class="container mt-5">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="card p-3">
                    <h5 class="section-heading">ประเภทสินค้า</h5>
                    <form id="filterForm">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="SKULLPANDA" id="skullpanda" name="category[]">
                            <label class="form-check-label" for="skullpanda">
                                กล่องสุ่ม
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="HIRONO" id="hirono" name="category[]">
                            <label class="form-check-label" for="hirono">
                                ตุ๊กตา
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="CRYBABY" id="crybaby" name="category[]">
                            <label class="form-check-label" for="crybaby">
                                กระเป๋า
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="MOLLY" id="molly" name="category[]">
                            <label class="form-check-label" for="molly">
                                อุปกรณ์เสริมโทรศัพท์
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">กรองสินค้า</button>
                    </form>
                </div>
            </div>

            <!-- ส่วนแสดงสินค้า -->
            <div class="col-lg-9">
                <h3 class="section-heading">สินค้าทั้งหมด</h3>
                <div class="row" id="productList">
                    <?php
                    // ตัวอย่างข้อมูลสินค้า (ในกรณีจริงควรดึงจากฐานข้อมูล)
                    $products = [
                        [
                            'id' => 1,
                            'name' => 'Skullpanda City of Night',
                            'price' => 850.00,
                            'image' => 'img/SKULLPANDA/SKULLPANDA1.jpg',
                            'category' => 'SKULLPANDA'
                        ],
                        [
                            'id' => 2,
                            'name' => 'HIRONO The Other One',
                            'price' => 850.00,
                            'image' => 'img/HIRONO/HIRONO5.jpg',
                            'category' => 'HIRONO'
                        ],
                        [
                            'id' => 3,
                            'name' => 'CRYBABY Night Series',
                            'price' => 750.00,
                            'image' => 'img/CRYBABY/CRYBABY1.jpg',
                            'category' => 'CRYBABY'
                        ],
                        [
                            'id' => 4,
                            'name' => 'MOLLY Space Series',
                            'price' => 790.00,
                            'image' => 'img/MOLLY/MOLLY2.jpg',
                            'category' => 'MOLLY'
                        ],
                        // เพิ่มสินค้าเพิ่มเติมที่นี่
                    ];

                    foreach ($products as $product): ?>
                        <div class="col-lg-4 col-md-6 mb-4 product-item" data-category="<?= $product['category'] ?>">
                            <div class="card h-100">
                                <img src="<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $product['name'] ?></h5>
                                    <p class="product-price">฿<?= number_format($product['price'], 2) ?></p>
                                    <div class="d-flex justify-content-between mt-3">
                                        <a href="product_details.php?id=<?= $product['id'] ?>" class="btn btn-primary">
                                            <i class="fas fa-eye me-1"></i> ดูรายละเอียด
                                        </a>
                                        <form method="POST" action="add_to_cart.php">
                                            <button type="submit" name="product_id" value="<?= $product['id'] ?>" class="btn btn-success">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript สำหรับการกรองสินค้า
        document.getElementById('filterForm').addEventListener('submit', function(event) {
            event.preventDefault(); // ป้องกันการโหลดหน้าใหม่

            const selectedCategories = [];
            document.querySelectorAll('input[name="category[]"]:checked').forEach(function(checkbox) {
                selectedCategories.push(checkbox.value);
            });

            const productItems = document.querySelectorAll('.product-item');
            productItems.forEach(function(item) {
                const productCategory = item.getAttribute('data-category');
                if (selectedCategories.length === 0 || selectedCategories.includes(productCategory)) {
                    item.style.display = 'block'; // แสดงสินค้า
                } else {
                    item.style.display = 'none'; // ซ่อนสินค้า
                }
            });
        });
    </script>
</body>

</html>