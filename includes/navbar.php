<!-- filepath: /c:/xampp/htdocs/Project_arttoy1/includes/navbar.php -->
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
                <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fas fa-home me-1"></i>
                        หน้าแรก</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-boxes me-1"></i>
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
                        <li class="nav-item">
                            <a class="nav-link nav-contact" href="profile.php">
                                <i class="fas fa-user me-1"></i> หน้าผู้ใช้
                            </a>
                        </li>
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