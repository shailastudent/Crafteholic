<?php
session_start();
include 'db_config.php';

// অ্যাডমিন চেক
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$order_id = intval($_GET['id']);
// অর্ডারের তথ্য আনা
$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id");
$order = mysqli_fetch_assoc($order_query);

if (!$order) {
    die("অর্ডার পাওয়া যায়নি!");
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order_id; ?> - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff; padding: 30px; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; }
        .print-btn { margin-bottom: 20px; }
        @media print {
            .print-btn { display: none; }
            body { padding: 0; }
            .invoice-box { border: none; }
        }
    </style>
</head>
<body>

    <div class="text-center print-btn">
        <button onclick="window.print()" class="btn btn-dark btn-lg">Print Invoice (PDF)</button>
        <a href="view_orders.php" class="btn btn-outline-secondary btn-lg">Back to Orders</a>
    </div>

    <div class="invoice-box shadow-sm">
        <div class="row">
            <div class="col-6">
                <h2 class="fw-bold">Crafteholic</h2>
                <p>হাতে তৈরি সব দারুণ কারুকাজ।</p>
            </div>
            <div class="col-6 text-end">
                <h4 class="text-uppercase text-muted">Invoice</h4>
                <p>Order ID: #<?php echo $order['id']; ?><br>
                Date: <?php echo date('d M, Y'); ?></p>
            </div>
        </div>

        <hr>

        <div class="row my-4">
            <div class="col-6">
                <h6 class="text-muted text-uppercase">Customer Info:</h6>
                <strong>Phone:</strong> <?php echo $order['phone']; ?><br>
                <strong>Address:</strong> <?php echo $order['address']; ?>
            </div>
        </div>

        <table class="table table-bordered mt-4">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product Subtotal</td>
                    <td class="text-end">৳ <?php echo $order['total_price']; ?></td>
                </tr>
                <tr>
                    <td>Delivery Charge & Others</td>
                    <td class="text-end">Included</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-end fs-5">Total Paid:</th>
                    <th class="text-end fs-5">৳ <?php echo $order['total_price']; ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="mt-5 text-center">
            <p class="text-muted small">Crafteholic এ কেনাকাটা করার জন্য ধন্যবাদ। <br> "No Cancellation Policy" প্রযোজ্য।</p>
        </div>
    </div>

</body>
</html>