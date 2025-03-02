<?php
include("include.php");
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: login.php?message=กรุณาเข้าสู่ระบบก่อน&status=warning");
    exit();
}

// ตัวแปรสำหรับการเชื่อมต่อฐานข้อมูลหรือการจัดการอื่นๆ
$cart_count = 0; // กำหนดจำนวนสินค้าที่อยู่ในตะกร้า
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}

// สมมติว่ามีการเชื่อมต่อฐานข้อมูลที่นี่
// $conn = mysqli_connect("localhost", "username", "password", "database_name");

// ข้อมูลผู้ใช้ (ในสถานการณ์จริงจะดึงจากฐานข้อมูล)
$user = $_SESSION['user'];
$user_id = $user['id'] ?? 1;
$name = $user['name'] ?? 'ชื่อผู้ใช้';
$email = $user['email'] ?? 'user@example.com';
$phone = $user['phone'] ?? '0xx-xxx-xxxx';
$address = $user['address'] ?? 'ที่อยู่ตัวอย่าง, กรุงเทพฯ';

// สมมติข้อมูลออเดอร์ (ในสถานการณ์จริงจะดึงจากฐานข้อมูล)
$orders = [
    [
        'id' => '1001',
        'date' => '2025-02-25',
        'total' => 1540,
        'status' => 'เตรียมจัดส่ง',
        'items' => [
            ['name' => 'Skullpanda City of Night', 'price' => 850, 'qty' => 1],
            ['name' => 'MOLLY Space Series', 'price' => 690, 'qty' => 1]
        ]
    ],
    [
        'id' => '1002',
        'date' => '2025-02-15',
        'total' => 1700,
        'status' => 'จัดส่งแล้ว',
        'items' => [
            ['name' => 'HIRONO The Other One', 'price' => 850, 'qty' => 2]
        ]
    ],
    [
        'id' => '1003',
        'date' => '2025-01-30',
        'total' => 1500,
        'status' => 'สำเร็จ',
        'items' => [
            ['name' => 'CRYBABY Night Series', 'price' => 750, 'qty' => 2]
        ]
    ]
];

// จัดการการอัปเดตข้อมูลผู้ใช้
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // ในสถานการณ์จริงจะมีการอัปเดตข้อมูลในฐานข้อมูล
    // $sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
    // $stmt->execute();
    
    // อัปเดตข้อมูลในเซสชัน
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['address'] = $address;
    
    // แสดงข้อความแจ้งเตือน
    $success_message = "อัปเดตข้อมูลสำเร็จ";
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บัญชีของฉัน - AAA MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/profile.css">
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
                        <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])): ?>
                            <a class="nav-link active" href="profile.php">
                                <i class="fas fa-user me-1"></i> บัญชีของฉัน
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
    <?php if (isset($success_message)): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success_message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= ($_GET['status'] ?? 'info') ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-user-circle me-2"></i>บัญชีของฉัน</h1>
                    <p class="lead">ยินดีต้อนรับ, <?= $name ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 profile-sidebar">
                <div class="profile-card">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle fa-5x text-primary mb-3"></i>
                        <h5><?= $name ?></h5>
                        <p class="text-muted"><?= $email ?></p>
                    </div>
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-orders-tab" data-bs-toggle="pill" href="#v-pills-orders" role="tab">
                            <i class="fas fa-shopping-bag me-2"></i>ประวัติการสั่งซื้อ
                        </a>
                        <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile" role="tab">
                            <i class="fas fa-user-edit me-2"></i>แก้ไขข้อมูลส่วนตัว
                        </a>
                        <a class="nav-link" id="v-pills-address-tab" data-bs-toggle="pill" href="#v-pills-address" role="tab">
                            <i class="fas fa-map-marker-alt me-2"></i>ที่อยู่จัดส่ง
                        </a>
                        <a class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill" href="#v-pills-password" role="tab">
                            <i class="fas fa-key me-2"></i>เปลี่ยนรหัสผ่าน
                        </a>
                        <a class="nav-link text-danger" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- ประวัติการสั่งซื้อ -->
                    <div class="tab-pane fade show active" id="v-pills-orders" role="tabpanel">
                        <div class="profile-card">
                            <h4 class="section-heading">ประวัติการสั่งซื้อ</h4>
                            
                            <?php if (empty($orders)): ?>
                                <div class="text-center p-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p>คุณยังไม่มีประวัติการสั่งซื้อ</p>
                                    <a href="products.php" class="btn btn-primary">เลือกซื้อสินค้า</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <div class="card order-card">
                                        <div class="order-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">คำสั่งซื้อ #<?= $order['id'] ?></h6>
                                                <small class="text-muted">วันที่: <?= date('d/m/Y', strtotime($order['date'])) ?></small>
                                            </div>
                                            <div>
                                                <?php
                                                    $statusClass = '';
                                                    switch ($order['status']) {
                                                        case 'เตรียมจัดส่ง':
                                                            $statusClass = 'status-preparing';
                                                            break;
                                                        case 'จัดส่งแล้ว':
                                                            $statusClass = 'status-shipped';
                                                            break;
                                                        case 'สำเร็จ':
                                                            $statusClass = 'status-completed';
                                                            break;
                                                    }
                                                ?>
                                                <span class="status-badge <?= $statusClass ?>"><?= $order['status'] ?></span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>สินค้า</th>
                                                            <th class="text-center">จำนวน</th>
                                                            <th class="text-end">ราคา</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($order['items'] as $item): ?>
                                                            <tr>
                                                                <td><?= $item['name'] ?></td>
                                                                <td class="text-center"><?= $item['qty'] ?></td>
                                                                <td class="text-end">฿<?= number_format($item['price'], 2) ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                    <tfoot class="table-light">
                                                        <tr>
                                                            <td colspan="2" class="text-end"><strong>รวมทั้งสิ้น:</strong></td>
                                                            <td class="text-end"><strong>฿<?= number_format($order['total'], 2) ?></strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-search me-1"></i> ดูรายละเอียด
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- แก้ไขข้อมูลส่วนตัว -->
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel">
                        <div class="profile-card">
                            <h4 class="section-heading">แก้ไขข้อมูลส่วนตัว</h4>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="name" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $name ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?= $phone ?>" required>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-update">
                                    <i class="fas fa-save me-2"></i>บันทึกข้อมูล
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- ที่อยู่จัดส่ง -->
                    <div class="tab-pane fade" id="v-pills-address" role="tabpanel">
                        <div class="profile-card">
                            <h4 class="section-heading">ที่อยู่จัดส่ง</h4>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="address" class="form-label">ที่อยู่</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required><?= $address ?></textarea>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-update">
                                    <i class="fas fa-save me-2"></i>บันทึกข้อมูล
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- เปลี่ยนรหัสผ่าน -->
                    <div class="tab-pane fade" id="v-pills-password" role="tabpanel">
                        <div class="profile-card">
                            <h4 class="section-heading">เปลี่ยนรหัสผ่าน</h4>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">รหัสผ่านปัจจุบัน</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" name="change_password" class="btn btn-update">
                                    <i class="fas fa-key me-2"></i>เปลี่ยนรหัสผ่าน
                                </button>
                            </form>
                        </div>
                    </div>
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