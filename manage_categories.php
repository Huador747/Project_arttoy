<?php
// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// รวมไฟล์เชื่อมต่อฐานข้อมูล
include("db_connection.php");

// ดึงข้อมูลหมวดหมู่
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<div class="page-header">
    <h2>Manage Categories</h2>
    <div class="d-flex">
        <a href="admin_panel.php?category=category&action=add" class="btn btn-add text-white">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>
</div>

<div class="table-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['category_name'] . "</td>";
                    echo "<td>
                        <a href='admin_panel.php?category=category&action=edit&id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                        <a href='admin_panel.php?category=category&action=delete&id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>No categories found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>