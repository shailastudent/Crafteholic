<?php
session_start();
include 'db_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
    $product = mysqli_fetch_assoc($product_query);

    if (!$product) { die("Product not found!"); }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .main-img { width: 100%; height: 500px; object-fit: cover; border-radius: 15px; cursor: zoom-in; }
        .thumb-img { width: 80px; height: 80px; object-fit: cover; cursor: pointer; border-radius: 8px; transition: 0.3s; border: 2px solid transparent; flex-shrink: 0; }
        .thumb-img:hover, .thumb-active { border: 2px solid #042342; transform: scale(1.05); }
        .description-box { background: #fff; padding: 25px; border-radius: 12px; border-left: 5px solid #2c3e50; }
        .btn-cart { transition: 0.3s; font-weight: 600; }
        .btn-cart:hover { background-color: #444; transform: scale(1.02); }

        /* Thumbnail Navigation Styles */
        .thumb-wrapper { position: relative; display: flex; align-items: center; margin-top: 15px; }
        .thumb-container { 
            display: flex; 
            gap: 10px; 
            overflow-x: hidden; 
            scroll-behavior: smooth;
            padding: 5px;
        }
        .nav-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #522828;
            cursor: pointer;
            z-index: 5;
            padding: 0 5px;
            transition: 0.3s;
        }
        .nav-btn:hover { color: #000; transform: scale(1.2); }

        /* WhatsApp Floating Button */
        .whatsapp-float {
            position: fixed; width: 60px; height: 60px; bottom: 20px; right: 20px;
            background-color: #25d366; color: #FFF; border-radius: 50px;
            display: flex; align-items: center; justify-content: center;
            font-size: 30px; box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
            z-index: 1000; text-decoration: none; transition: 0.3s;
        }
        .whatsapp-float:hover { background-color: #128c7e; transform: scale(1.1); color: #fff; }

        /* Discount Badge */
        .offer-badge {
            background: #ff4757;
            color: white;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row g-5">
        <div class="col-md-6">
            <a href="image/<?php echo $product['image']; ?>" data-lightbox="product-gallery" id="mainImgLink">
                <img src="image/<?php echo $product['image']; ?>" class="main-img shadow" id="currentImg" alt="<?php echo $product['name']; ?>">
            </a>
            
            <div class="thumb-wrapper">
                <button class="nav-btn" onclick="scrollThumb(-100)"><i class="bi bi-chevron-left"></i></button>
                <div class="thumb-container" id="thumbContainer">
                    <img src="image/<?php echo $product['image']; ?>" class="thumb-img thumb-active" onclick="changeImg(this.src, this)">
                    <?php
                    $gallery_query = mysqli_query($conn, "SELECT * FROM product_images WHERE product_id = '$id'");
                    if(mysqli_num_rows($gallery_query) > 0) {
                        while($img_row = mysqli_fetch_assoc($gallery_query)) {
                            $final_path = "image/" . trim($img_row['image_name']); 
                            echo '<img src="'.$final_path.'" class="thumb-img shadow-sm" onclick="changeImg(this.src, this)">';
                        }
                    }
                    ?>
                </div>
                <button class="nav-btn" onclick="scrollThumb(100)"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-dark">Home</a></li>
                    <li class="breadcrumb-item active"><?php echo $product['name']; ?></li>
                </ol>
            </nav>

            <h1 class="fw-bold display-6"><?php echo $product['name']; ?></h1>

            <div class="mb-4">
                <?php 
                // ডিসকাউন্ট প্রাইস চেক করা
                if (!empty($product['discount_price']) && $product['discount_price'] < $product['price']): 
                    $discount_percent = round((($product['price'] - $product['discount_price']) / $product['price']) * 100);
                ?>
                    <h3 class="text-danger mb-1 fw-bold">৳ <?php echo number_format($product['discount_price'], 2); ?></h3>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted text-decoration-line-through">৳ <?php echo number_format($product['price'], 2); ?></span>
                        <span class="offer-badge"><?php echo $discount_percent; ?>% ছাড়!</span>
                    </div>
                <?php else: ?>
                    <h3 class="text-danger mb-4 fw-bold">৳ <?php echo number_format($product['price'], 2); ?></h3>
                <?php endif; ?>
            </div>

            <div class="description-box shadow-sm mb-4">
                <h5 class="fw-bold"><i class="bi bi-info-circle-fill me-2"></i>কেন এই প্রোডাক্টটি কিনবেন?</h5>
                <div class="text-muted mt-3" style="font-size: 1.05rem; line-height: 1.6;">
                    <?php echo !empty($product['description']) ? nl2br(htmlspecialchars($product['description'])) : "আমাদের এই হ্যান্ডমেড প্রোডাক্টটি অত্যন্ত যত্ন সহকারে তৈরি করা হয়েছে।"; ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn btn-dark btn-lg rounded-pill shadow-sm btn-cart">
                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                </a>
            </div>
            
            <div class="mt-4 p-3 bg-white rounded shadow-sm border">
                <p class="small text-muted mb-2"><i class="bi bi-truck me-2 text-primary"></i> <strong>ডেলিভারি সময়:</strong> ২-৩ কার্যদিবস (ঢাকার ভিতরে)</p>
                <p class="small text-muted mb-0"><i class="bi bi-shield-check me-2 text-success"></i> <strong>গ্যারান্টি:</strong> ১০০% কোয়ালিটি নিশ্চিত ডিজাইন</p>
            </div>
        </div>
    </div>
</div>

<?php 
    $whatsapp_num = "8801766462429"; 
    $whatsapp_msg = urlencode("আসসালামু আলাইকুম, আমি এই প্রোডাক্টটি সম্পর্কে জানতে চাই: " . $product['name'] . " (ID: " . $id . ")");
?>

<a href="https://wa.me/<?php echo $whatsapp_num; ?>?text=<?php echo $whatsapp_msg; ?>" class="whatsapp-float" target="_blank">
    <i class="bi bi-whatsapp"></i>
</a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
    function changeImg(src, element) {
        document.getElementById('currentImg').src = src;
        document.getElementById('mainImgLink').href = src;
        document.querySelectorAll('.thumb-img').forEach(img => img.classList.remove('thumb-active'));
        element.classList.add('thumb-active');
    }

    function scrollThumb(val) {
        document.getElementById('thumbContainer').scrollLeft += val;
    }
</script>
</body>
</html>