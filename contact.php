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
    <title>AAA MART - ติดต่อเรา</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/contact.css">
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
                    <li class="nav-item"><a class="nav-link active" href="contact.php"><i class="fas fa-envelope me-1"></i>
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
                    <h1 class="hero-heading">ติดต่อเรา</h1>
                    <p class="lead mb-4">เรายินดีช่วยเหลือคุณทุกคำถามและข้อสงสัย</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ส่วนข้อมูลติดต่อและฟอร์ม -->
    <div class="container mt-5">
        <div class="row">
            <!-- ข้อมูลติดต่อ -->
            <div class="col-md-6">
                <div class="contact-section">
                    <h3 class="section-heading">ข้อมูลการติดต่อ</h3>
                    <p><i class="fas fa-map-marker-alt contact-icon"></i> ที่อยู่: 123 ถนนตัวอย่าง, กรุงเทพฯ</p>
                    <p><i class="fas fa-phone contact-icon"></i> โทร: 02-123-4567</p>
                    <p><i class="fas fa-envelope contact-icon"></i> อีเมล: contact@aaamart.com</p>
                    <div class="mt-4">
                        <h5>ช่องทางติดต่ออื่นๆ</h5>
                        <a href="#" class="btn btn-outline-dark me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-dark me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-dark me-2"><i class="fab fa-line"></i></a>
                    </div>
                </div>
            </div>

            <!-- ฟอร์มติดต่อ -->
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="contact-section">
                    <h3 class="section-heading">ส่งข้อความถึงเรา</h3>
                    <form action="send_message.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">หัวข้อ</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">ข้อความ</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">ส่งข้อความ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>