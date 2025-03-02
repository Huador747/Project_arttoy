<?php
session_start();
include("include.php");

// ตรวจสอบการล็อกอินและบทบาท
if (!isset($_SESSION['user']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// ตรวจสอบหมวดหมู่ที่เลือก
$category = isset($_GET['category']) ? $_GET['category'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - AAA MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_panel.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar bg-light">
                <div class="admin-profile text-center py-4">
                    <img src="img/admin_avatar.jpg" alt="Admin Profile" class="img-fluid rounded-circle mb-2">
                    <h3>Hello, Admin</h3>
                </div>
                <nav class="nav flex-column">
                    <a href="admin_panel.php?category=dashboard" class="nav-link <?php echo $category == 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="admin_panel.php?category=customers" class="nav-link <?php echo $category == 'customers' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Customers
                    </a>
                    <a href="admin_panel.php?category=category" class="nav-link <?php echo $category == 'category' ? 'active' : ''; ?>">
                        <i class="fas fa-th-large"></i> Category
                    </a>
                    <a href="admin_panel.php?category=products" class="nav-link <?php echo $category == 'products' ? 'active' : ''; ?>">
                        <i class="fas fa-box"></i> Products
                    </a>
                    <a href="admin_panel.php?category=orders" class="nav-link <?php echo $category == 'orders' ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-cart"></i> Orders
                    </a>
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <?php
                // แสดงข้อความสถานะ
                if (isset($_GET['status']) && isset($_GET['message'])) {
                    echo '<div class="alert alert-' . ($_GET['status'] == 'success' ? 'success' : 'danger') . '">' . $_GET['message'] . '</div>';
                }

                // เรียกใช้ไฟล์ตามหมวดหมู่
                switch ($category) {
                    case 'dashboard':
                        include('dashboard.php');
                        break;
                    case 'customers':
                        include('manage_customers.php');
                        break;
                    case 'category':
                        include('manage_categories.php');
                        break;
                    case 'products':
                        include('manage_products.php');
                        break;
                    case 'orders':
                        include('manage_orders.php');
                        break;
                    default:
                        include('dashboard.php');
                        break;
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>