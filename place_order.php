<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $final_total = mysqli_real_escape_string($conn, $_POST['final_total']);
    
    // ১. আইডি ধরার জন্য ৩টি ব্যাকআপ লজিক
    if (!empty($_POST['product_id'])) {
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    } elseif (!empty($_SESSION['cart'])) {
        // যদি ফর্ম থেকে না আসে, সেশনের কার্ট থেকে প্রথম আইডি নিবে
        $product_id = mysqli_real_escape_string($conn, reset($_SESSION['cart']));
    } else {
        $product_id = 0; 
    }

    // ২. নিশ্চিত করা যে product_id খালি বা NULL না থাকে
    if ($product_id == 0 || empty($product_id)) {
        die("Error: Product ID পাওয়া যায়নি। দয়া করে আবার চেষ্টা করুন।");
    }

    // ৩. ইনসার্ট কুয়েরি
    $sql = "INSERT INTO orders (user_id, product_id, total_price, address, phone, status, order_date) 
            VALUES ('$user_id', '$product_id', '$final_total', '$address', '$phone', 'Pending', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        unset($_SESSION['cart']);
        unset($_SESSION['coupon_discount']);
        unset($_SESSION['applied_coupon']);

        echo "<script>
                alert('Order Successful! Your Order ID: " . mysqli_insert_id($conn) . "');
                window.location='my_orders.php'; 
              </script>";
    } else {
        die("Database Error: " . mysqli_error($conn));
    }
} else {
    header("Location: checkout.php");
}
?>