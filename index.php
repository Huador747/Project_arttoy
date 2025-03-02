<?php
include("include.php");
// เริ่ม Session
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
    <title>AAA MART - เว็บไซต์ขายของออนไลน์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<body>

    <!-- แถบเมนูนำทาง -->
    <?php include 'includes/navbar.php'; ?>

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
                <div class="col-lg-6">
                    <h1 class="hero-heading">ค้นพบโลกของตัวละครสะสม</h1>
                    <p class="lead mb-4">เพลิดเพลินไปกับคอลเลคชั่นตัวการ์ตูนสะสมยอดนิยมในราคาที่คุ้มค่า พร้อมจัดส่งถึงบ้านทั่วประเทศ</p>
                    <a href="products.php" class="btn btn-light btn-lg">ดูสินค้าทั้งหมด <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="img/bannerall.png" alt="Hero Image" class="img-fluid rounded" style="max-height: 300px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>

    <!-- หมวดหมู่สินค้า -->
    <div class="container mt-5">
        <h3 class="section-heading">หมวดหมู่ยอดนิยม</h3>
        <div class="row">
        <?php
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="col-md-3 col-6">
                <a href="products.php?category=' . $row['id'] . '" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-gift"></i></div>
                        <h5>' . $row['category_name'] . '</h5>
                    </div>
                </a>
              </div>';
    }
} else {
    echo "0 results";
}
?>
       

    <!-- สินค้าขายดี -->
    <div class="container mt-5">
        <h3 class="section-heading">สินค้าขายดี</h3>
        <div class="row">
        <?php
$sql = "SELECT * FROM products LIMIT 4";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="col-lg-3 col-md-6">
                <div class="card h-100">
                    <img src="' . $row['image'] . '" class="card-img-top" alt="' . $row['product_name'] . '">
                    <div class="card-body">
                        <h5 class="card-title">' . $row['product_name'] . '</h5>
                        <p class="product-price">฿' . $row['price'] . '</p>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="product_details.php?id=' . $row['id'] . '" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i> ดูรายละเอียด
                            </a>
                            <form method="POST" action="add_to_cart.php">
                                <button type="submit" name="product_id" value="' . $row['id'] . '" class="btn btn-success">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
              </div>';
    }
} else {
    echo "0 results";
}
?>
           
        <div class="text-center mt-4">
            <a href="products.php" class="btn btn-outline-primary">ดูสินค้าทั้งหมด <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>

    <!-- Promotion Banner -->
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="row g-0 align-items-center">
            <div class="col-md-8">
                <img src="img/bannerall.png" class="img-fluid rounded-start" alt="Promotion">
            </div>
            <div class="col-md-4 text-center p-4">
                <h3 class="fw-bold">โปรโมชั่นพิเศษ!</h3>
                <p class="lead">ซื้อสินค้าครบ 1,500 บาท รับส่วนลดทันที 10%</p>
                <p>พิเศษ! สำหรับสมาชิกใหม่ รับส่วนลดเพิ่ม 5%</p>
                <a href="promotion.php" class="btn btn-danger">ดูรายละเอียด</a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>