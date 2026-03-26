<?php
session_start();
include 'db_config.php';

$code = mysqli_real_escape_string($conn, $_POST['code']);
$total_cart_amount = $_POST['total']; // কার্টের বর্তমান টোটাল

$query = "SELECT * FROM coupons WHERE code = '$code' AND status = 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $coupon = mysqli_fetch_assoc($result);
    
    // মেয়াদ শেষ হয়েছে কিনা চেক (ঐচ্ছিক)
    if ($coupon['expiry_date'] && $coupon['expiry_date'] < date('Y-m-d')) {
        echo json_encode(['status' => 'error', 'msg' => 'কুপনটির মেয়াদ শেষ!']);
        exit;
    }

    $discount = 0;
    if ($coupon['discount_type'] == 'fixed') {
        $discount = $coupon['discount_amount'];
    } else {
        $discount = ($total_cart_amount * $coupon['discount_amount']) / 100;
    }

    $_SESSION['coupon_discount'] = $discount;
    $_SESSION['applied_coupon'] = $code;

    echo json_encode(['status' => 'success', 'discount' => $discount, 'new_total' => ($total_cart_amount - $discount)]);
} else {
    echo json_encode(['status' => 'error', 'msg' => 'ভুল কুপন কোড!']);
}
?>