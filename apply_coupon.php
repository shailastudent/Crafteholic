<?php
session_start();
include 'db_config.php'; 

if (isset($_POST['code'])) {
    $code = mysqli_real_escape_string($conn, strtoupper(trim($_POST['code'])));

    $query = "SELECT * FROM coupons WHERE code = '$code' AND status = 1 LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['coupon_discount'] = $row['discount_amount']; 
        echo "success";
    } else {
        unset($_SESSION['coupon_discount']);
        echo "invalid";
    }
}
?>