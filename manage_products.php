<?php
session_start();
include 'db_config.php';

// অ্যাডমিন চেক
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// ডিলিট করার লজিক
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); 
    if(mysqli_query($conn, "DELETE FROM products WHERE id = $id")) {
        header("Location: manage_products.php?msg=deleted");
        exit();
    }
}

// স্টক আপডেট করার লজিক
if (isset($_POST['update_stock'])) {
    $p_id = intval($_POST['product_id']);
    $new_stock = intval($_POST['stock_quantity']);
    mysqli_query($conn, "UPDATE products SET stock = $new_stock WHERE id = $p_id");
    header("Location: manage_products.php?msg=updated");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .alert-fixed {
            position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;
        }
        .stock-input { width: 70px !important; text-align: center; border-radius: 5px 0 0 5px; }
        .stock-btn { border-radius: 0 5px 5px 0; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        
        <?php if (isset($_GET['msg'])): ?>
            <div id="statusAlert" class="alert alert-success alert-dismissible fade show shadow alert-fixed" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php 
                    if($_GET['msg'] == 'deleted') echo "প্রোডাক্টটি সফলভাবে মুছে ফেলা হয়েছে!";
                    if($_GET['msg'] == 'updated') echo "তথ্য আপডেট করা হয়েছে!";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
            <h3 class="mb-0 fw-bold text-dark"><i class="bi bi-box-seam me-2"></i>Manage Your Products</h3>
            <a href="admin_dashboard.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white shadow-sm align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Current Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <img src="image/<?php echo $row['image']; ?>" width="60" height="60" class="rounded shadow-sm" style="object-fit: cover;">
                        </td>
                        <td class="fw-bold text-start ps-4"><?php echo $row['name']; ?></td>
                        <td class="text-success fw-bold">৳ <?php echo number_format($row['price'], 2); ?></td>
                        
                        <td>
                            <form action="manage_products.php" method="POST" class="d-flex justify-content-center">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <input type="number" name="stock_quantity" class="form-control stock-input" value="<?php echo $row['stock']; ?>" min="0">
                                    <button type="submit" name="update_stock" class="btn btn-dark stock-btn" title="Update Stock">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </div>
                            </form>
                            <?php if($row['stock'] <= 5): ?>
                                <small class="text-danger fw-bold"><i class="bi bi-exclamation-triangle"></i> Low Stock!</small>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="btn-group shadow-sm">
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="manage_products.php?delete=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(function() {
            let alert = document.getElementById('statusAlert');
            if (alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    </script>
</body>
</html>