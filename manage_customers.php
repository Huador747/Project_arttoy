<?php
// ตรวจสอบการล็อกอิน
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// รวมไฟล์เชื่อมต่อฐานข้อมูล
include("../include.php");

// ดึงข้อมูลลูกค้า
$sql = "SELECT * FROM users WHERE role = 'customer'";
$result = $conn->query($sql);
?>

<div class="page-header">
    <h2>Manage Customers</h2>
</div>

<div class="table-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "<td>
                        <a href='admin_panel.php?category=customers&action=edit&id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                        <a href='admin_panel.php?category=customers&action=delete&id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No customers found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>