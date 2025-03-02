<?php
include("include.php");
session_start();

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

$error = '';
$success = false;

// Process registration form
if (isset($_POST['register'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "กรุณากรอกข้อมูลทั้งหมด";
    } elseif ($password !== $confirm_password) {
        $error = "รหัสผ่านไม่ตรงกัน";
    } else {
        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "อีเมลนี้มีผู้ใช้แล้ว";
            } else {
                // Close the statement
                $stmt->close();

                // Insert new user into the database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password

                $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("sss", $name, $email, $hashed_password);
                    if ($stmt->execute()) {
                        $success = true;
                        header("Location: login.php?registered=1");
                        exit();
                    } else {
                        $error = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
                    }
                } else {
                    $error = "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
                }
            }
        } else {
            $error = "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
        }
    }
    $conn->close(); // Close the connection
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - AAAMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container" style="max-width: 600px; margin-top: 50px;">
        <h2 class="text-center">สมัครสมาชิก</h2>

        <?php if ($success): ?>
            <div class="alert alert-success">สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ</div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="d-grid">
                <button type="submit" name="register" class="btn btn-primary">สมัครสมาชิก</button>
            </div>
        </form>
    </div>
</body>
</html>