<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// অর্ডার ডিলিট লজিক
if (isset($_GET['delete_order'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['delete_order']);
    $u_id = $_SESSION['user_id'];
    $delete_sql = "DELETE FROM orders WHERE id = '$order_id' AND user_id = '$u_id'";
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Order deleted successfully!'); window.location='my_orders.php';</script>";
    }
}

$user_id = $_SESSION['user_id'];

// SQL JOIN: orders এবং products টেবিল থেকে ডাটা আনা হচ্ছে
$sql = "SELECT orders.id as oid, orders.order_date, orders.status, orders.total_price, 
               products.name as p_name, products.image as p_image 
        FROM orders 
        LEFT JOIN products ON orders.product_id = products.id 
        WHERE orders.user_id = '$user_id' 
        ORDER BY orders.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>My Orders - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Crafteholic</a>
            <a href="index.php" class="btn btn-outline-dark btn-sm">Back to Home</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="mb-4 text-center">আমার অর্ডারসমূহ (My Orders)</h3>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="card shadow-sm border-0">
                <table class="table align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th> <th>Order ID</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <img src="image/<?php echo $row['p_image']; ?>" class="product-img me-2 border" alt="Product">
                                    <span class="small fw-bold"><?php echo $row['p_name']; ?></span>
                                </div>
                            </td>
                            <td>#<?php echo $row['oid']; ?></td>
                            <td><?php echo date('d M, Y', strtotime($row['order_date'])); ?></td>
                            <td class="fw-bold">৳ <?php echo $row['total_price']; ?></td>
                            <td>
                                <span class="badge <?php echo $row['status'] == 'Pending' ? 'bg-warning text-dark' : 'bg-success'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="my_orders.php?delete_order=<?php echo $row['oid']; ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('আপনি কি নিশ্চিত?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">কোনো অর্ডার পাওয়া যায়নি।</div>
        <?php endif; ?>
    </div>
</body>
</html>