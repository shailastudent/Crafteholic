<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to place an order!'); window.location='login.php';</script>";
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$ids = implode(',', $_SESSION['cart']);
$res = mysqli_query($conn, "SELECT SUM(price) as total FROM products WHERE id IN ($ids)");
$row = mysqli_fetch_assoc($res);
$subtotal = $row['total'];

$first_product_id = reset($_SESSION['cart']); 
$discount = isset($_SESSION['coupon_discount']) ? $_SESSION['coupon_discount'] : 0;
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); }
        .coupon-box { display: flex; gap: 10px; margin-bottom: 25px; background: #fff; padding: 15px; border-radius: 12px; border: 1px solid #eee; }
        .summary-total { font-weight: 800; font-size: 1.5rem; border-top: 2px solid #eee; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card p-5">
                    <h2 class="text-center mb-5 fw-bold">Confirm Your Order</h2>
                    
                    <form action="place_order.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $first_product_id; ?>">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="017XXXXXXXX" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Delivery Area</label>
                            <select name="area" id="delivery_area" class="form-select" onchange="updateTotal()" required>
                                <option value="" selected disabled>এলাকা নির্বাচন করুন</option>
                                <option value="60">Inside Dhaka (৳ 60)</option>
                                <option value="120">Outside Dhaka (৳ 120)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Full Address</label>
                            <textarea name="address" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="coupon-box">
                            <input type="text" id="coupon_code" class="form-control" placeholder="Coupon Code">
                            <button class="btn btn-outline-dark" type="button" onclick="applyCoupon()">Apply</button>
                        </div>

                        <div class="p-4 rounded-3 border bg-white mb-4">
                            <h4 class="fw-bold border-bottom pb-2">Order Summary</h4>
                            <div class="d-flex justify-content-between mb-2"><span>Subtotal:</span> ৳<?php echo $subtotal; ?></div>
                            <div class="d-flex justify-content-between text-success mb-2"><span>Discount:</span> -৳<?php echo $discount; ?></div>
                            <div class="d-flex justify-content-between mb-2"><span>Shipping:</span> +৳<span id="display_shipping">0</span></div>
                            <div class="summary-total d-flex justify-content-between">
                                <span>Total Payable:</span>
                                <span>৳ <span id="display_total"><?php echo ($subtotal - $discount); ?></span></span>
                            </div>
                        </div>

                        <input type="hidden" name="final_total" id="input_total" value="<?php echo ($subtotal - $discount); ?>">
                        <button type="submit" class="btn btn-dark w-100 py-3 rounded-pill fw-bold">Place Order Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function updateTotal() {
        let subtotal = <?php echo (float)$subtotal; ?>;
        let discount = <?php echo (float)$discount; ?>;
        let shipping = parseInt(document.getElementById('delivery_area').value) || 0;
        let finalTotal = (subtotal - discount) + shipping;
        document.getElementById('display_shipping').innerText = shipping;
        document.getElementById('display_total').innerText = finalTotal;
        document.getElementById('input_total').value = finalTotal;
    }

    function applyCoupon() {
        let code = $('#coupon_code').val();
        if(code === "") return;
        $.ajax({
            url: 'apply_coupon.php',
            method: 'POST',
            data: {code: code, total: <?php echo $subtotal; ?>},
            success: function(response) { location.reload(); }
        });
    }
</script>
</body>
</html>