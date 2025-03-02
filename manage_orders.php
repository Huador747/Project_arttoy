<?php
// ตรวจสอบการล็อกอิน
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// รวมไฟล์เชื่อมต่อฐานข้อมูล
include("../include.php");

// ดึงข้อมูลคำสั่งซื้อ
$sql = "SELECT o.id, u.name, o.order_date, o.total_amount, o.status 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
?>

<div class="page-header">
    <h2>Manage Orders</h2>
</div>

<div class="table-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>#" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "<td>฿" . number_format($row['total_amount'], 2) . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>
                        <a href='admin_panel.php?category=orders&action=view&id=" . $row['id'] . "' class='btn btn-sm btn-primary'>View</a>
                        <a href='admin_panel.php?category=orders&action=update&id=" . $row['id'] . "' class='btn btn-sm btn-success'>Update</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No orders found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>