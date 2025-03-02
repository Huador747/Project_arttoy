<?php
// Include the database connection file
include_once '../config/db.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// ตรวจสอบการดำเนินการ (เพิ่ม, แก้ไข, ลบ)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($action == 'delete' && $id) {
        // ลบสินค้า
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: admin_panel.php?category=products&status=success&message=Product deleted successfully");
            exit();
        } else {
            header("Location: admin_panel.php?category=products&status=error&message=Failed to delete product");
            exit();
        }
    }
}

// ดึงข้อมูลสินค้า
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<div class="page-header">
    <h2>Manage Products</h2>
    <div class="d-flex">
        <a href="admin_panel.php?category=products&action=add" class="btn btn-add text-white">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>
</div>

<div class="table-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['product_name'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>฿" . number_format($row['price'], 2) . "</td>";
                    echo "<td>" . $row['stock'] . "</td>";
                    echo "<td>
                        <a href='admin_panel.php?category=products&action=edit&id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                        <a href='admin_panel.php?category=products&action=delete&id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No products found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>