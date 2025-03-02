<?php
include("include.php");
session_start();

// ตรวจสอบการล็อกอินและบทบาท
if (!isset($_SESSION['user']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aaamart_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ฟังก์ชันตัวอย่างสำหรับดึงข้อมูล
function getTotalCustomers($conn) {
    $sql = "SELECT COUNT(*) as total FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getTotalProducts($conn) {
    $sql = "SELECT COUNT(*) as total FROM products";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getTotalOrders($conn) {
    $sql = "SELECT COUNT(*) as total FROM orders";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getTotalRevenue($conn) {
    $sql = "SELECT SUM(total_price) as total FROM orders";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AAA MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_panel.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <div class="row mt-4">
            <!-- Card 1: จำนวนลูกค้า -->
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">จำนวนลูกค้า</h5>
                        <p class="card-text"><?php echo getTotalCustomers($conn); ?> คน</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: จำนวนสินค้า -->
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">จำนวนสินค้า</h5>
                        <p class="card-text"><?php echo getTotalProducts($conn); ?> ชิ้น</p>
                    </div>
                </div>
            </div>

            <!-- Card 3: จำนวนคำสั่งซื้อ -->
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">จำนวนคำสั่งซื้อ</h5>
                        <p class="card-text"><?php echo getTotalOrders($conn); ?> อันดับ</p>
                    </div>
                </div>
            </div>

            <!-- Card 4: รายได้รวม -->
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">รายได้รวม</h5>
                        <p class="card-text"><?php echo getTotalRevenue($conn); ?> บาท</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- กราฟหรือตารางข้อมูลเพิ่มเติม -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">สถิติการขายล่าสุด</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ตัวอย่างกราฟสถิติการขาย
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน'],
                datasets: [{
                    label: 'ยอดขาย',
                    data: [12000, 19000, 3000, 5000, 2000, 3000],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>