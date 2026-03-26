<?php
session_start();
include 'db_config.php';

// চেক করা হচ্ছে ইউজার অ্যাডমিন কি না
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<script>alert('Access Denied!'); window.location='index.php';</script>";
    exit();
}

// লো-স্টক প্রোডাক্ট চেক করা (যাদের স্টক ৫ বা তার কম)
$low_stock_query = mysqli_query($conn, "SELECT name, stock FROM products WHERE stock <= 5");
$low_stock_count = mysqli_num_rows($low_stock_query);

// ১. মোট আয় (শুধুমাত্র Completed অর্ডারগুলো)
$income_query = mysqli_query($conn, "SELECT SUM(total_price) as total_income FROM orders WHERE status = 'Completed'");
$income_data = mysqli_fetch_assoc($income_query);
$total_income = $income_data['total_income'] ?? 0;

// ২. পেন্ডিং অর্ডারের সংখ্যা
$pending_query = mysqli_query($conn, "SELECT COUNT(*) as pending_orders FROM orders WHERE status = 'Pending'");
$pending_data = mysqli_fetch_assoc($pending_query);
$pending_count = $pending_data['pending_orders'];

// ৩. মোট প্রোডাক্টের সংখ্যা
$product_count_query = mysqli_query($conn, "SELECT COUNT(*) as total_products FROM products");
$product_count_data = mysqli_fetch_assoc($product_count_query);
$total_products = $product_count_data['total_products'];
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .header-box { border-radius: 20px; border: none; }
        .card { transition: 0.4s; border: none; border-radius: 18px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.12); }
        .stat-card { border-bottom: 4px solid; }
        .btn-home { background-color: #f8f9fa; color: #333; border: 1px solid #ddd; }
        .btn-home:hover { background-color: #e9ecef; }
        .alert-custom { border-radius: 15px; border-left: 6px solid #ffc107; }
    </style>
</head>
<body>
    <div class="container mt-5 pb-5">
        <div class="header-box d-flex justify-content-between align-items-center bg-white p-4 shadow-sm mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
                <p class="text-muted mb-0"><i class="bi bi-shield-lock-fill me-1"></i> Crafteholic Control panel</p>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-home rounded-pill px-4 shadow-sm">
                    <i class="bi bi-house-door me-1"></i> Home
                </a>
                <a href="logout.php" class="btn btn-danger rounded-pill px-4 shadow-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>

        <?php if ($low_stock_count > 0): ?>
    <div class="row justify-content-left"> <div class="col-md-8"> <div class="alert bg-white border-0 shadow-sm rounded-pill mb-4 py-2 px-4" style="border-left: 5px solid #e38b11a2 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill text-warning fs-5 me-2"></i>
                        <span class="small fw-bold text-dark me-2">Stock Alert:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <?php 
                            mysqli_data_seek($low_stock_query, 0); 
                            while($item = mysqli_fetch_assoc($low_stock_query)): 
                            ?>
                                <span class="badge bg-light text-danger border rounded-pill" style="font-size: 11px;">
                                    <?php echo $item['name']; ?> (<?php echo $item['stock']; ?>)
                                </span>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <a href="manage_products.php" class="btn btn-sm btn-link text-decoration-none fw-bold text-warning p-0 small">
                        Update <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
        <div class="row g-3 mb-5 text-center">
            <div class="col-md-3">
                <div class="card bg-white p-4 stat-card" style="border-bottom-color: #212529;">
                    <h6 class="text-muted text-uppercase fw-bold small">Total Earnings</h6>
                    <h2 class="fw-bold text-dark mb-0">৳ <?php echo number_format($total_income, 2); ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-white p-4 stat-card" style="border-bottom-color: #dc3545;">
                    <h6 class="text-muted text-uppercase fw-bold small">Pending Orders</h6>
                    <h2 class="fw-bold text-danger mb-0"><?php echo $pending_count; ?> টি</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-white p-4 stat-card" style="border-bottom-color: #0dcaf0;">
                    <h6 class="text-muted text-uppercase fw-bold small">Active Products</h6>
                    <h2 class="fw-bold text-info mb-0"><?php echo $total_products; ?> টি</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-white p-4 stat-card" style="border-bottom-color: <?php echo ($low_stock_count > 0) ? '#ffc107' : '#198754'; ?>;">
                    <h6 class="text-muted text-uppercase fw-bold small">Stock Alert</h6>
                    <h2 class="fw-bold mb-0 <?php echo ($low_stock_count > 0) ? 'text-warning' : 'text-success'; ?>">
                        <?php echo $low_stock_count; ?> <small class="fs-6">Items</small>
                    </h2>
                </div>
            </div>
        </div>
        
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card bg-primary text-white p-4 h-100">
                    <div class="mb-3"><i class="bi bi-plus-circle-dotted fs-1"></i></div>
                    <h4 class="fw-bold">Add Product</h4>
                    <p class="small opacity-75">নতুন প্রোডাক্ট বা গিফট আইটেম আপলোড করুন।</p>
                    <a href="add_product.php" class="btn btn-light w-100 mt-auto fw-bold text-primary rounded-pill">Go to Add</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-warning text-dark p-4 h-100">
                    <div class="mb-3"><i class="bi bi-kanban fs-1"></i></div>
                    <h4 class="fw-bold">Manage Products</h4>
                    <p class="small opacity-75">প্রোডাক্টের নাম, দাম এডিট বা ডিলিট করুন।</p>
                    <a href="manage_products.php" class="btn btn-dark w-100 mt-auto fw-bold rounded-pill">Go to Manage</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-success text-white p-4 h-100">
                    <div class="mb-3"><i class="bi bi-bag-check fs-1"></i></div>
                    <h4 class="fw-bold">Customer Orders</h4>
                    <p class="small opacity-75">অর্ডার তালিকা এবং ডেলিভারি স্ট্যাটাস দেখুন।</p>
                    <a href="view_orders.php" class="btn btn-light w-100 mt-auto text-success fw-bold rounded-pill">View All Orders</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>