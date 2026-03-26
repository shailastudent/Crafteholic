<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    exit('Access Denied');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_id = intval($_POST['product_id']);
    $new_stock = intval($_POST['stock_quantity']);

    $sql = "UPDATE products SET stock = $new_stock WHERE id = $p_id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: manage_products.php?msg=Stock Updated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>