<?php
session_start();
include 'db_config.php';

// শুধুমাত্র অ্যাডমিন ঢুকতে পারবে
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// অর্ডারের স্ট্যাটাস আপডেট করার লজিক
if (isset($_GET['complete'])) {
    $order_id = intval($_GET['complete']);
    mysqli_query($conn, "UPDATE orders SET status = 'Completed' WHERE id = $order_id");
    header("Location: view_orders.php");
    exit();
}

// --- নতুন যোগ করা ডিলিট লজিক ---
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM orders WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Order Deleted Successfully!'); window.location='view_orders.php';</script>";
    } else {
        echo "<script>alert('Error deleting order!');</script>";
    }
}

// ৩টি টেবিল জয়েন করে ডেটা আনা (orders, users, products)
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
        .product-thumb { width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; }
        .badge { padding: 8px 12px; border-radius: 50px; font-weight: 500; }
        .btn-action { display: flex; gap: 5px; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark"><i class="bi bi-cart-check me-2"></i>Customer Orders</h2>
            <a href="admin_dashboard.php" class="btn btn-outline-dark shadow-sm">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </div>

        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Product</th> <th>Customer</th>
                            <th>Total Price</th>
                            <th>Shipping Info</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?php echo $row['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="image/<?php echo $row['p_image']; ?>" class="product-thumb me-2" alt="p_img">
                                    <span class="small fw-semibold"><?php echo $row['p_name']; ?></span>
                                </div>
                            </td>
                            <td><?php echo $row['customer_name']; ?></td>
                            <td class="fw-bold text-primary">৳ <?php echo number_format($row['total_price'], 2); ?></td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i><?php echo $row['address']; ?><br>
                                    <i class="bi bi-telephone-fill text-primary me-1"></i><?php echo $row['phone']; ?>
                                </div>
                            </td>
                            <td>
                                <?php if($row['status'] == 'Pending'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-action justify-content-center">
                                    <a href="print_invoice.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Print Invoice">
                                        <i class="bi bi-printer"></i>
                                    </a>

                                    <?php if($row['status'] == 'Pending'): ?>
                                        <a href="view_orders.php?complete=<?php echo $row['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('আপনি কি এই অর্ডারটি কমপ্লিট হিসেবে মার্ক করতে চান?')">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="view_orders.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('সাবধান! আপনি কি নিশ্চিতভাবে এই অর্ডারটি ডিলিট করতে চান?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php if(mysqli_num_rows($result) == 0): ?>
                <div class="text-center p-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-2 text-muted">এখনো কোনো অর্ডার পাওয়া যায়নি।</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>