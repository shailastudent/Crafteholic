<?php
session_start();
include 'db_config.php';

// ১. লগইন চেক
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // এখানে পরিবর্তন করা হয়েছে: 'area' এর বদলে ফর্মের 'address' ফিল্ডটি নেওয়া হয়েছে
    $address = mysqli_real_escape_string($conn, $_POST['address']); 
    
    $final_total = mysqli_real_escape_string($conn, $_POST['final_total']);
    $coupon_discount = isset($_POST['coupon_amount']) ? mysqli_real_escape_string($conn, $_POST['coupon_amount']) : 0;
    
    if (!empty($_SESSION['cart'])) {
        $product_id = mysqli_real_escape_string($conn, implode(',', $_SESSION['cart']));
    } else {
        die("Error: Cart is empty. Sorry! Order not possible");
    }

    // SQL কুয়েরি: এখানে $address ভেরিয়েবলে এখন ইউজারের টাইপ করা সঠিক ঠিকানা জমা হবে
    $sql = "INSERT INTO orders (user_id, product_id, total_price, address, phone, status, order_date) 
            VALUES ('$user_id', '$product_id', '$final_total', '$address', '$phone', 'Pending', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        unset($_SESSION['cart']); 
        unset($_SESSION['coupon_discount']); 
        
        echo "<script>
                alert('Order Successful! Your Order ID: " . mysqli_insert_id($conn) . "');
                window.location='my_orders.php'; 
              </script>";
    } else {
        die("Database Error: " . mysqli_error($conn));
    }
} else {
    header("Location: checkout.php");
    exit();
}
?>