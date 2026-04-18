<?php
session_start();
include 'db_config.php';

if (isset($_GET['remove'])) {
    $id_to_remove = $_GET['remove'];
    if (($key = array_search($id_to_remove, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); 
    }
    header("Location: view_cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Crafteholic</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-bg: #f4f6f9; 
            --accent-color: #6c5ce7; 
            --text-main: #2d3436;
        }

        body {
            background-color: var(--primary-bg);
            font-family: 'Poppins', sans-serif;
            color: var(--text-main);
            letter-spacing: 0.3px;
        }

       
        h1, h2, h3, .fw-bold {
            font-weight: 700 !important;
            color: #1e272e;
        }

        
        .card {
            border: none !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
            background: #ffffff;
        }

        .container {
            max-width: 1100px;
        }

    
        .btn-success {
            background-color: #203da5 !important;
            border: none !important;
            padding: 12px 30px !important;
            border-radius: 12px !important;
            transition: 0.3s all ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Crafteholic</a>
            <a href="index.php" class="btn btn-outline-dark btn-sm">Continue Shopping</a>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4">Shopping Cart</h3>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Product</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $ids = implode(',', $_SESSION['cart']);
                            $sql = "SELECT * FROM products WHERE id IN ($ids)";
                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_assoc($result)):
                                $total += $row['price'];
                            ?>
                            <tr>
                                <td><img src="image/<?php echo $row['image']; ?>" width="70" class="rounded"></td>
                                <td class="fw-bold"><?php echo $row['name']; ?></td>
                                <td>৳ <?php echo $row['price']; ?></td>
                                <td>
                                    <a href="view_cart.php?remove=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total Amount:</td>
                                <td colspan="2" class="fw-bold text-success">৳ <?php echo $total; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="text-end mt-4">
                <a href="checkout.php" class="btn btn-success btn-lg px-5">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center py-5">
                <h4>Your cart is empty!</h4>
                <a href="index.php" class="btn btn-primary mt-3">Go Shop Now</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>