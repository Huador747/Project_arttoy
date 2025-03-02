    <?php
    session_start();

    // Check if user is logged in and is admin
    if(!isset($_SESSION['user']) || $_SESSION['user_role'] != 'admin') {
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

    // Handle product size actions
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        
        // Delete product size
        if($action == 'delete' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM product_sizes WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if($stmt->execute()) {
                header("Location: admin_panel.php?category=productsizes&status=success&message=Size deleted successfully");
                exit();
            } else {
                header("Location: admin_panel.php?category=productsizes&status=error&message=Failed to delete size");
                exit();
            }
        }
        
        // Add or update product size
        if(($action == 'add' || $action == 'edit') && isset($_POST['submit'])) {
            $product_name = $_POST['product_name'];
            $size = $_POST['size'];
            $stock = $_POST['stock'];
            
            if($action == 'add') {
                $sql = "INSERT INTO product_sizes (product_name, size, stock) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $product_name, $size, $stock);
            } else {
                $id = $_POST['id'];
                $sql = "UPDATE product_sizes SET product_name = ?, size = ?, stock = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssii", $product_name, $size, $stock, $id);
            }
            
            if($stmt->execute()) {
                header("Location: admin_panel.php?category=productsizes&status=success&message=Size " . ($action == 'add' ? 'added' : 'updated') . " successfully");
                exit();
            } else {
                header("Location: admin_panel.php?category=productsizes&status=error&message=Failed to " . ($action == 'add' ? 'add' : 'update') . " size");
                exit();
            }
        }
    }

    // Get current category
    $category = isset($_GET['category']) ? $_GET['category'] : 'dashboard';

    // Message handling
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $message = isset($_GET['message']) ? $_GET['message'] : '';
    ?>

    <!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Panel - AAA MART</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body {
                font-family: 'Kanit', sans-serif;
                background-color: #f8f9fa;
            }
            
            .sidebar {
                height: 100vh;
                background-color: #343a40;
                color: white;
                position: fixed;
                width: 250px;
                padding-top: 20px;
            }
            
            .main-content {
                margin-left: 250px;
                padding: 20px;
            }
            
            .nav-link {
                color: rgba(255, 255, 255, 0.8);
                transition: all 0.3s;
                padding: 10px 20px;
                margin: 5px 0;
            }
            
            .nav-link:hover, .nav-link.active {
                color: white;
                background-color: rgba(255, 255, 255, 0.1);
                border-left: 3px solid #ff6b6b;
            }
            
            .nav-link i {
                margin-right: 10px;
                width: 20px;
                text-align: center;
            }
            
            .admin-profile {
                text-align: center;
                padding: 20px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                margin-bottom: 20px;
            }
            
            .admin-profile img {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                object-fit: cover;
                margin-bottom: 10px;
            }
            
            .admin-profile h3 {
                font-size: 1.2rem;
                margin-bottom: 0;
            }
            
            .table-container {
                background-color: white;
                border-radius: 5px;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            
            .action-btn {
                margin: 0 3px;
            }
            
            .page-header {
                background-color: #4e4e4e;
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                margin-bottom: 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .btn-add {
                background-color: #36b9cc;
                border: none;
            }
            
            .status-message {
                margin-bottom: 20px;
            }
            
            .form-container {
                background-color: white;
                border-radius: 5px;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
            }
        </style>
        <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="admin-profile">
                <img src="img/admin_avatar.jpg" alt="Admin Profile">
                <h3>Hello, Admin</h3>
            </div>
            <nav>
                <a href="admin_panel.php?category=dashboard" class="nav-link <?php echo $category == 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="admin_panel.php?category=customers" class="nav-link <?php echo $category == 'customers' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Customers
                </a>
                <a href="admin_panel.php?category=category" class="nav-link <?php echo $category == 'category' ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i> Category
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
        <div class="main-content">
            <?php if($status && $message): ?>
            <div class="alert alert-<?php echo $status == 'success' ? 'success' : 'danger'; ?> status-message">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <?php if($category == 'dashboard'): ?>
                <div class="page-header">
                    <h2>Dashboard</h2>
                    <div class="d-flex">
                        <button class="btn btn-primary me-2">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Total Products</h5>
                                        <?php
                                        $result = $conn->query("SELECT COUNT(*) as count FROM products");
                                        $row = $result->fetch_assoc();
                                        ?>
                                        <h2><?php echo $row['count']; ?></h2>
                                    </div>
                                    <i class="fas fa-box fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Total Orders</h5>
                                        <?php
                                        $result = $conn->query("SELECT COUNT(*) as count FROM orders");
                                        $row = $result->fetch_assoc();
                                        ?>
                                        <h2><?php echo $row['count']; ?></h2>
                                    </div>
                                    <i class="fas fa-shopping-cart fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Total Customers</h5>
                                        <?php
                                        $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'");
                                        $row = $result->fetch_assoc();
                                        ?>
                                        <h2><?php echo $row['count']; ?></h2>
                                    </div>
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Out of Stock</h5>
                                        <?php
                                        $result = $conn->query("SELECT COUNT(*) as count FROM product_sizes WHERE stock = 0");
                                        $row = $result->fetch_assoc();
                                        ?>
                                        <h2><?php echo $row['count']; ?></h2>
                                    </div>
                                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="table-container">
                            <h4 class="mb-4">Recent Orders</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT o.id, u.name, o.order_date, o.total_amount, o.status 
                                            FROM orders o 
                                            JOIN users u ON o.user_id = u.id 
                                            ORDER BY o.order_date DESC LIMIT 5";
                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>#" . $row["id"] . "</td>";
                                            echo "<td>" . $row["name"] . "</td>";
                                            echo "<td>" . $row["order_date"] . "</td>";
                                            echo "<td>฿" . number_format($row["total_amount"], 2) . "</td>";
                                            echo "<td>";
                                            if($row["status"] == "completed") {
                                                echo "<span class='badge bg-success'>Completed</span>";
                                            } else if($row["status"] == "processing") {
                                                echo "<span class='badge bg-primary'>Processing</span>";
                                            } else {
                                                echo "<span class='badge bg-warning'>Pending</span>";
                                            }
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No recent orders</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="table-container">
                            <h4 class="mb-4">Low Stock Products</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT product_name, size, stock FROM product_sizes WHERE stock <= 5 ORDER BY stock ASC LIMIT 5";
                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["product_name"] . "</td>";
                                            echo "<td>" . $row["size"] . "</td>";
                                            echo "<td>" . $row["stock"] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center'>No low stock products</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($category == 'productsizes'): ?>
                <div class="page-header">
                    <h2>Product Sizes</h2>
                    <div class="d-flex">
                        <a href="admin_panel.php?category=productsizes&action=add" class="btn btn-add text-white">
                            <i class="fas fa-plus"></i> Add New Size
                        </a>
                    </div>
                </div>
                
                <?php if(isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')): ?>
                    <?php 
                    $product_name = '';
                    $size = '';
                    $stock = '';
                    $id = '';
                    
                    if($_GET['action'] == 'edit' && isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $sql = "SELECT * FROM product_sizes WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $product_name = $row['product_name'];
                            $size = $row['size'];
                            $stock = $row['stock'];
                        }
                    }
                    ?>                        </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $stock; ?>" min="0" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                            <a href="admin_panel.php?category=productsizes" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>S.N.</th>
                                    <th>Product Name</th>
                                    <th>Size</th>
                                    <th>Stock Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM product_sizes ORDER BY id";
                                $result = $conn->query($sql);
                                
                                if ($result->num_rows > 0) {
                                    $sn = 1;
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $sn++ . "</td>";
                                        echo "<td>" . $row["product_name"] . "</td>";
                                        echo "<td>" . $row["size"] . "</td>";
                                        echo "<td>" . $row["stock"] . "</td>";
                                        echo "<td>
                                            <a href='admin_panel.php?category=productsizes&action=edit&id=" . $row["id"] . "' class='btn btn-sm btn-primary action-btn'>Edit</a>
                                            <a href='admin_panel.php?category=productsizes&action=delete&id=" . $row["id"] . "' class='btn btn-sm btn-danger action-btn' onclick='return confirm(\"Are you sure you want to delete this size?\")'>Delete</a>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No product sizes found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Other category sections would go here -->
            
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    $conn->close();
    ?>