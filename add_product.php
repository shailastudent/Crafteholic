<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    
    // ইমেজ আপলোড প্রসেস
    $target_dir = "image/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$image_name')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Product Added Successfully!'); window.location='admin_dashboard.php';</script>";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 card shadow p-4">
                <h3 class="mb-4">Add New Craft Product</h3>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Price (৳)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Product Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <div class="mb-3">
    <label class="form-label">Product Description</label>
    <textarea name="description" class="form-control" rows="4" placeholder="কেন কাস্টমার এই প্রোডাক্টটি কিনবে?"></textarea>
</div>
                    <button type="submit" class="btn btn-success w-100">Upload Product</button>
                    <a href="admin_dashboard.php" class="btn btn-link w-100 mt-2">Back to Dashboard</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>