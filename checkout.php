<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login!'); window.location='login.php';</script>";
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$ids = implode(',', array_map('intval', $_SESSION['cart']));
$query = "SELECT SUM(CASE WHEN discount_price > 0 THEN discount_price ELSE price END) as total FROM products WHERE id IN ($ids)";
$res = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($res);
$main_price = $row['total'] ?? 0;

$coupon_discount = isset($_SESSION['coupon_discount']) ? (float)$_SESSION['coupon_discount'] : 0;
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --clay-primary: #8E5431; 
            --clay-light: #D2B48C;   
            --clay-bg: #F5F0E1;      
            --clay-text: #4A2F1B;    
        }

        body { 
            background-color: var(--clay-bg); 
            font-family: 'Segoe UI', Roboto, sans-serif; 
            color: var(--clay-text);
        }

        .checkout-card { 
            border: none; 
            border-radius: 25px; 
            box-shadow: 0 15px 35px rgba(142, 84, 49, 0.1); 
            background: #fff; 
            overflow: hidden;
        }

        .checkout-header {
            background-color: var(--clay-primary);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .coupon-box { 
            background: #FAF9F6; 
            border: 2px dashed var(--clay-light); 
            border-radius: 15px; 
            padding: 20px; 
            margin-bottom: 25px;
            display: none; 
        }

        .form-control, .form-select { 
            border-radius: 12px; 
            padding: 12px; 
            border: 1px solid var(--clay-light);
            background-color: #fff;
        }

        .form-check-input:checked {
            background-color: var(--clay-primary);
            border-color: var(--clay-primary);
        }

        .summary-box {
            background-color: #FDFBF7;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid #F0E6D2;
        }

        .summary-item { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 12px; 
            font-size: 1.05rem; 
        }

        .total-section { 
            border-top: 2px solid var(--clay-light); 
            padding-top: 15px; 
            margin-top: 15px; 
            color: var(--clay-primary);
        }

        .btn-confirm { 
            background: var(--clay-primary); 
            color: #fff; 
            border-radius: 15px; 
            padding: 16px; 
            font-weight: 700; 
            transition: all 0.4s ease; 
            border: none;
            width: 100%;
        }

        .btn-confirm:hover { 
            background: #6D3F24; 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(109, 63, 36, 0.3); 
            color: #fff;
        }

        .btn-apply {
            background-color: var(--clay-text);
            color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-9">
            <div class="checkout-card">
                <div class="checkout-header">
                    <h2 class="fw-bold mb-0"><i class="fa-solid fa-leaf me-2"></i>Crafteholic</h2>
                </div>

                <div class="p-4 p-md-5">
                    <div class="mb-4 text-center">
                        <label class="fw-bold d-block mb-2">Do you have any coupon code?</label>
                        <div class="d-flex justify-content-center gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="has_coupon" id="coupon_yes" value="yes" <?php echo ($coupon_discount > 0) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="coupon_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="has_coupon" id="coupon_no" value="no" <?php echo ($coupon_discount == 0) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="coupon_no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="coupon-box" id="coupon_input_container">
                        <label class="fw-bold d-block text-center mb-2">Use Coupon Code</label>
                        <div class="input-group">
                            <input type="text" id="coupon_code" class="form-control text-center" placeholder="e.g. TAJIA100">
                            <button class="btn btn-apply px-4" type="button" onclick="applyCoupon()">Apply</button>
                        </div>
                    </div>

                    <form action="place_order.php" method="POST">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="fw-bold"><i class="fa-solid fa-phone-volume me-1"></i> Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="fw-bold"><i class="fa-solid fa-map-location-dot me-1"></i> Delivery area</label>
                                <select name="area" id="delivery_area" class="form-select" onchange="updateTotal()" required>
                                    <option value="" selected disabled>Select your area...</option>
                                    <option value="60">Dhaka city (৳60)</option>
                                    <option value="120">Outside Dhaka (৳120)</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="fw-bold"><i class="fa-solid fa-home me-1"></i> Full address</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="House no, Road no, City" required></textarea>
                            </div>
                        </div>

                        <div class="summary-box">
                            <div class="summary-item">
                                <span>Product price</span>
                                <span>৳<?php echo number_format($main_price, 2); ?></span>
                            </div>

                            <?php if ($coupon_discount > 0): ?>
                            <div class="summary-item text-success fw-bold">
                                <span><i class="fa-solid fa-tag me-1"></i> Coupon discount</span>
                                <span>-৳<?php echo number_format($coupon_discount, 2); ?></span>
                            </div>
                            <?php endif; ?>

                            <div class="summary-item border-bottom pb-2">
                                <span>Delivery Charge</span>
                                <span>+৳<span id="display_shipping">0.00</span></span>
                            </div>

                            <div class="summary-item total-section">
                                <span class="fs-4 fw-bold">Total</span>
                                <span class="fs-4 fw-bold">৳<span id="display_total"><?php echo number_format($main_price - $coupon_discount, 2); ?></span></span>
                            </div>
                        </div>

                        <input type="hidden" name="coupon_amount" value="<?php echo $coupon_discount; ?>">
                        <input type="hidden" name="final_total" id="input_total" value="<?php echo ($main_price - $coupon_discount); ?>">

                        <button type="submit" class="btn btn-confirm mt-4">
                            Confirm Order <i class="fa-solid fa-check-circle ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        if ($('#coupon_yes').is(':checked')) {
            $('#coupon_input_container').show();
        }

        $('input[name="has_coupon"]').change(function() {
            if ($(this).val() === 'yes') {
                $('#coupon_input_container').slideDown();
            } else {
                $('#coupon_input_container').slideUp();
            }
        });
    });

    function updateTotal() {
        let mainPrice = <?php echo (float)$main_price; ?>;
        let couponDiscount = <?php echo (float)$coupon_discount; ?>;
        let shipping = parseInt(document.getElementById('delivery_area').value) || 0;
        let finalTotal = (mainPrice - couponDiscount) + shipping;
        
        document.getElementById('display_shipping').innerText = shipping.toFixed(2);
        document.getElementById('display_total').innerText = finalTotal.toFixed(2);
        document.getElementById('input_total').value = finalTotal;
    }

    function applyCoupon() {
        let code = $('#coupon_code').val();
        if(!code) return alert("Please enter coupon code!");

        $.ajax({
            url: 'apply_coupon.php',
            method: 'POST',
            data: { code: code },
            success: function(res) {
                if(res.trim() === "success") {
                    location.reload();
                } else {
                    alert("Wrong coupon code!");
                }
            }
        });
    }
</script>
</body>
</html>