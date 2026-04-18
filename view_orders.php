<?php
session_start();
include 'db_config.php';

// শুধুমাত্র অ্যাডমিন ঢুকতে পারবে
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// ১. অর্ডারের স্ট্যাটাস আপডেট করার লজিক (Completed করা)
if (isset($_GET['complete'])) {
    $order_id = intval($_GET['complete']);
    mysqli_query($conn, "UPDATE orders SET status = 'Completed' WHERE id = $order_id");
    header("Location: view_orders.php");
    exit();
}

// ২. ডিলিট লজিক (সরাসরি এই ফাইল থেকেই ডিলিট হবে)
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    // ডাটাবেস থেকে রো মুছে ফেলার কুয়েরি
    $delete_query = "DELETE FROM orders WHERE id = $delete_id";
    
    if (mysqli_query($conn, $delete_query)) {
        // ডিলিট হওয়ার পর একটি সাকসেস মেসেজ দেখাবে এবং পেজ রিফ্রেশ হবে
        echo "<script>alert('Order Deleted Successfully!'); window.location='view_orders.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting order: " . mysqli_error($conn) . "');</script>";
    }
}

// ডাটা আনা (JOIN কুয়েরি ব্যবহার করে)
$sql = "SELECT orders.*, users.name as customer_name, 
               products.name as p_name, products.image as p_image 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        LEFT JOIN products ON orders.product_id = products.id 
        ORDER BY orders.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 50px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        .header-title { color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .table thead { background: #2d3436; color: white; }
        .product-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 10px; }
        .badge { padding: 8px 15px; border-radius: 50px; font-size: 0.8rem; }
        .price-text { color: #2ecc71; font-weight: 700; font-size: 1.1rem; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold header-title">
                <i class="bi bi-bag-check-fill me-2"></i>Order Management
            </h2>
            <a href="admin_dashboard.php" class="btn btn-light shadow-sm rounded-pill px-4 fw-bold">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Product</th> 
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Contact & Delivery</th> 
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?php echo $row['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="image/<?php echo $row['p_image']; ?>" class="product-thumb me-3">
                                    <span class="fw-bold text-dark small"><?php echo $row['p_name']; ?></span>
                                </div>
                            </td>
                            <td class="fw-semibold"><?php echo $row['customer_name']; ?></td>
                            <td><span class="price-text">৳ <?php echo number_format($row['total_price'], 0); ?></span></td>
                            
                            <td>
                                <div class="small">
                                    <i class="bi bi-geo-alt-fill text-danger"></i> <?php echo $row['address']; ?><br>
                                    <i class="bi bi-telephone-fill text-primary"></i> <?php echo $row['phone']; ?>
                                </div>
                            </td>

                            <td class="text-center">
                                <?php if($row['status'] == 'Pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Completed</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a href="print_invoice.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-printer"></i>
                                </a>

                                <?php if($row['status'] == 'Pending'): ?>
                                    <a href="view_orders.php?complete=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                <?php endif; ?>

                                <a href="view_orders.php?delete=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('আপনি কি নিশ্চিত যে এই অর্ডারটি ডিলিট করতে চান? ডিলিট করলে এটি ড্যাশবোর্ড থেকেও মুছে যাবে।')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php if(mysqli_num_rows($result) == 0): ?>
                <div class="text-center p-5">
                    <i class="bi bi-cart-x fs-1 text-muted"></i>
                    <p class="mt-2 text-muted fw-bold">No orders found!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>