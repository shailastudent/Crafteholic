<?php
session_start();
include 'db_config.php';

// শুধুমাত্র অ্যাডমিন ঢুকতে পারবে
if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin'){
    header("Location: index.php");
    exit();
}

// স্ট্যাটাস আপডেট করার লজিক
if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id='$order_id'");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-processing { background-color: #17a2b8; color: #fff; }
        .badge-delivered { background-color: #28a745; color: #fff; }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <h2 class="mb-4 fw-bold text-dark">Manage Orders (Crafteholic)</h2>
        
        <div class="table-container shadow-sm">
            <table class="table align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)){
                            // ডাটাবেসে কলামের নাম ভিন্ন হলেও যাতে এরর না আসে তার জন্য এই চেক:
                            $customer = $row['user_name'] ?? $row['name'] ?? $row['customer_name'] ?? 'Unknown';
                            $amount = $row['total_price'] ?? $row['price'] ?? $row['total_amount'] ?? '0';
                            $current_status = $row['status'] ?? 'Pending';
                    ?>
                    <tr>
                        <td><strong>#<?php echo $row['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($customer); ?></td>
                        <td>৳ <?php echo htmlspecialchars($amount); ?></td>
                        <td>
                            <?php 
                                $badge_class = 'badge-pending';
                                if($current_status == 'Delivered') $badge_class = 'badge-delivered';
                                if($current_status == 'Processing') $badge_class = 'badge-processing';
                            ?>
                            <span class="badge <?php echo $badge_class; ?> p-2">
                                <?php echo $current_status; ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                    <option value="Pending" <?php if($current_status == 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Processing" <?php if($current_status == 'Processing') echo 'selected'; ?>>Processing</option>
                                    <option value="Delivered" <?php if($current_status == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                </select>
                                <button name="update_status" class="btn btn-sm btn-dark px-3">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4 text-muted'>কোনো অর্ডার পাওয়া যায়নি।</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="index.php" class="btn btn-link mt-3 text-secondary text-decoration-none">← Back to Shop Homepage</a>
    </div>
</body>
</html>