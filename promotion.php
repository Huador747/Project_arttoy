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
    <title>AAA MART - โปรโมชั่น</title>
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
                <div class="col-lg-12 text-center">
                    <h1 class="hero-heading">โปรโมชั่นพิเศษ</h1>
                    <p class="lead mb-4">พบกับข้อเสนอสุดพิเศษที่คุณไม่ควรพลาด!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ส่วนโปรโมชั่น -->
    <div class="container mt-5">
        <h3 class="section-heading">โปรโมชั่นทั้งหมด</h3>
        <div class="row">
            <!-- โปรโมชั่น 1 -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <img src="img/bannerall.png" alt="Promotion" class="img-fluid rounded-start" style="height: 100%; object-fit: cover;">
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="p-4">
                                <h3 class="mb-3">โปรโมชั่นพิเศษซื้อครบลดไปเลย!</h3>
                                <p class="lead">ซื้อสินค้าครบ 1,500 บาท รับส่วนลดทันที 10%</p>
                                <p>พิเศษ! สำหรับสมาชิกใหม่ รับส่วนลดเพิ่ม 5%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- โปรโมชั่น 2 -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <img src="img/bannerall.png" alt="Promotion" class="img-fluid rounded-start" style="height: 100%; object-fit: cover;">
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="p-4">
                                <h3 class="mb-3">โปรโมชั่นพิเศษเอาใจลูกค้าใหม่!</h3>
                                <h5 class="card-title">ส่วนลด 10% สำหรับสมาชิกใหม่</h5>
                                <p class="card-text">สมัครสมาชิกใหม่วันนี้ รับส่วนลดทันที 10% สำหรับการซื้อครั้งแรก</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- โปรโมชั่น 3 -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <img src="img/bannerall.png" alt="Promotion" class="img-fluid rounded-start" style="height: 100%; object-fit: cover;">
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="p-4">
                                <h5 class="mb-3">โปรโมชั่นพิเศษส่งฟรีสุดคุ้ม!</h5>
                                <h5 class="card-title">ฟรีค่าจัดส่ง</h5>
                                <p class="card-text">สั่งซื้อสินค้าครบ 2,000 บาท รับฟรีค่าจัดส่งทั่วประเทศ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>