<?php
include("include.php");
session_start();


// Check if user is already logged in
if (isset($_SESSION['user'])) {
    if ($_SESSION['user_role'] == 'admin') {
        header("Location: admin_panel.php");
    } else {
        header("Location: index.php");
    }
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

$error = '';
$registered = isset($_GET['registered']) && $_GET['registered'] == '1';

// Process login form
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $error = "กรุณากรอกอีเมลและรหัสผ่าน";
    } else {
        // Check user in database
        $sql = "SELECT * FROM users WHERE email = ?";

        // Prepare and execute the statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email); // Bind email parameter as string
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();

                    // Verify password
                    if (password_verify($password, $user['password'])) {
                        // Set session variables
                        $_SESSION['user'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_role'] = $user['role'];

                        // Redirect based on role
                        if ($user['role'] == 'admin') {
                            header("Location: admin_panel.php");
                        } else {
                            header("Location: index.php");
                        }
                        exit();
                    } else {
                        // หากรหัสผ่านไม่ตรง ให้ลองตรวจสอบรหัสผ่านแบบไม่เข้ารหัส (ชั่วคราว)
                        if ($password === $user['password']) {
                            // Set session variables
                            $_SESSION['user'] = $user['id'];
                            $_SESSION['user_name'] = $user['name'];
                            $_SESSION['user_role'] = $user['role'];

                            // Redirect based on role
                            if ($user['role'] == 'admin') {
                                header("Location: admin_panel.php");
                            } else {
                                header("Location: index.php");
                            }
                            exit();
                        } else {
                            $error = "รหัสผ่านไม่ถูกต้อง";
                        }
                    }
                } else {
                    $error = "ไม่พบอีเมลนี้ในระบบ";
                }
            } else {
                $error = "เกิดข้อผิดพลาดในการค้นหาข้อมูลผู้ใช้: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - AAAMart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">

</head>
<body>
    <div class="container login-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>เข้าสู่ระบบ</h3>
                    </div>
                    <div class="card-body">
                        <!-- Display success message when registered -->
                        <?php if ($registered) : ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Display error message -->
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Login form -->
                        <form action="" method="POST">
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i> อีเมล
                                </label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="กรอกอีเมลของคุณ" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> รหัสผ่าน
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control password-field" id="password" name="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                                    <span class="input-group-text" id="togglePassword">
                                        <i class="fas fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="forgot-password">
                                <a href="#"><i class="fas fa-question-circle me-1"></i>ลืมรหัสผ่าน?</a>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="login" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>เข้าสู่ระบบ
                                </button>
                            </div>
                            <div class="register-link">
                                ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิกใหม่</a>
                            </div>
                            
                            
                <div class="login-footer">
                    &copy; <?php echo date('Y'); ?> AAAMart. สงวนลิขสิทธิ์
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const toggleIcon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        });
    </script>
</body>
</html>