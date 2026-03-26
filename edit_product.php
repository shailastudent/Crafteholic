<?php
session_start();
include 'db_config.php';

// অ্যাডমিন চেক
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']); // নিরাপত্তার জন্য intval ব্যবহার করা ভালো
$product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
$product = mysqli_fetch_assoc($product_query);

// আপডেট করার লজিক
if (isset($_POST['update_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    
    // ডিসকাউন্ট প্রাইস চেক
    $discount_price = $_POST['discount_price'];
    if ($discount_price === "" || $discount_price === null) {
        $discount_sql_val = "NULL";
    } else {
        $discount_sql_val = "'" . mysqli_real_escape_string($conn, $discount_price) . "'";
    }

    // SQL আপডেট কুয়েরি (discount_price সহ)
    $update_sql = "UPDATE products SET 
                   name='$name', 
                   price='$price', 
                   discount_price=$discount_sql_val, 
                   description='$desc' 
                   WHERE id='$id'";
    
    if (mysqli_query($conn, $update_sql)) {
        header("Location: manage_products.php?msg=updated");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-label { font-weight: 600; color: #333; }
        .btn-update { background-color: #2c3e50; color: white; transition: 0.3s; }
        .btn-update:hover { background-color: #1a252f; color: white; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 bg-white p-4 shadow-sm rounded">
                <h3 class="mb-4 text-center">Edit Product: <?php echo $product['name']; ?></h3>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Regular Price (৳)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Price (৳)</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control" value="<?php echo $product['discount_price']; ?>" placeholder="অফার না থাকলে খালি রাখুন">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?php echo $product['description']; ?></textarea>
                    </div>

                    <button type="submit" name="update_product" class="btn btn-update w-100 py-2">Update Product Details</button>
                    <a href="manage_products.php" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>