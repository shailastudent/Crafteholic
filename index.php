<?php
session_start();
include 'db_config.php';
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crafteholic - Handmade Paradise</title>
    <link rel="icon" type="image/png" href="image/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        html { scroll-behavior: smooth; }
        .navbar .form-control { border-radius: 20px; }
        .navbar .btn-search { border-radius: 20px; }
        .product-card img { height: 350px; object-fit: cover; cursor: pointer; }
        #shop-section { scroll-margin-top: 100px; }

        #backToTop {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: none;
            z-index: 1000;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            transition: 0.3s;
            font-size: 20px;
        }
        
        .product-card {
            transition: transform 0.3s ease, shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }

        .typing::after {
            content: "|";
            animation: blink 0.7s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        .review-card {
            background: #fff;
            border-radius: 12px;
            transition: 0.3s;
        }

        /* Load More বাটনের জন্য বাড়তি স্টাইল */
        #loadMore {
            background-color: #5d4037; /* আপনার লোগোর খয়েরি থিমের সাথে মিল রেখে */
            border: none;
            transition: 0.3s;
        }
        #loadMore:hover {
            background-color: #3e2723;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container">
           <a class="navbar-brand brand-text d-flex align-items-center" href="index.php">
    <img src="image/logo.png" alt="Crafteholic Logo" width="50" height="50" class="d-inline-block align-top me-2 rounded-circle border shadow-sm">
    Crafteholic
</a>
            
            <form action="index.php" method="GET" class="d-flex mx-lg-auto my-2 my-lg-0" style="max-width: 400px; width: 100%;">
                <input class="form-control me-2" type="search" name="search" placeholder="Search crafts..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-outline-dark btn-search" type="submit">Search</button>
            </form>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#shop-section">Shop</a></li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="catDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="catDropdown">
                            <li><a class="dropdown-item" href="index.php?category=Bouquet ">Bouquets Items</a></li>
                            <li><a class="dropdown-item" href="index.php?category=Resin ">Resin Items</a></li>
                            <li><a class="dropdown-item" href="index.php?category=Book">Scrapbooks Items</a></li>
                            <li><a class="dropdown-item" href="index.php?category=Hijab">Hijab Items</a></li>
                            <li><a class="dropdown-item" href="index.php?category=Canvas">Canvas Items</a></li>
                            <li><a class="dropdown-item" href="index.php?category=Jar">Jar Items</a></li>
                            <li><a class="dropdown-item" href="index.php?category=Card">Card Items</a></li>
                            <li><a class="dropdown-item" href="index.php?category=Box">Box Items</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link position-relative" href="view_cart.php">
                            Cart 
                            <span class="badge rounded-pill bg-danger">
                                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn btn-outline-dark ms-lg-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                Hi, <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="my_orders.php">My Orders</a></li>
                                <?php if($_SESSION['user_role'] == 'admin'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="admin_dashboard.php">Admin Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-dark ms-lg-2" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center py-5 bg-light mb-4">
        <div class="container">
            <h1 class="display-4 fw-bold">Explore Unique Crafts</h1>
            <p class="lead typing">Handmade with love, just for you.</p>
        </div>
    </header>

    <main id="shop-section" class="container my-5">
        <h2 class="text-center mb-4">
            <?php 
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    echo "Search Results for '".htmlspecialchars($_GET['search'])."'";
                } elseif (isset($_GET['category'])) {
                    echo htmlspecialchars($_GET['category']) . " Collection";
                } else {
                    echo "Featured Products";
                }
            ?>
        </h2>
        
        <div class="row g-4" id="product-container">
            <?php
            $sql = "SELECT * FROM products ORDER BY id DESC";
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search_query = mysqli_real_escape_string($conn, $_GET['search']);
                $sql = "SELECT * FROM products WHERE name LIKE '%$search_query%' ORDER BY id DESC";
            } elseif (isset($_GET['category'])) {
                $category = mysqli_real_escape_string($conn, $_GET['category']);
                $sql = "SELECT * FROM products WHERE name LIKE '%$category%' ORDER BY id DESC";
            }

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="col-md-4 col-sm-6 product-item">
                        <div class="card h-100 product-card border-0 shadow-sm">
                            <a href="product_details.php?id=<?php echo $row['id']; ?>">
                                <img src="image/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                            </a>
                            <div class="card-body text-center">
                                <h5 class="card-title fw-semibold"><?php echo $row['name']; ?></h5>
                                <p class="card-text text-muted">৳ <?php echo $row['price']; ?></p>
                                <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn btn-dark w-100 mt-2">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-12 text-center'><p class='alert alert-warning'>No products found!</p></div>";
            }
            ?>
        </div>

        <div class="text-center mt-5">
            <button id="loadMore" class="btn btn-dark px-5 py-2 fw-bold shadow-sm">Load More Crafts</button>
        </div>
    </main>

   <section class="container my-5">
    <div class="bg-white shadow-sm p-4 rounded text-center">
        <h3 class="fw-bold mb-4" style="font-family: 'Playfair Display';">Our Happy Customers</h3>
        
        <div class="row g-4 mb-4">
            <?php
            $rev_sql = "SELECT * FROM reviews ORDER BY id DESC LIMIT 3";
            $rev_result = mysqli_query($conn, $rev_sql);
            if (mysqli_num_rows($rev_result) > 0) {
                while($rev_row = mysqli_fetch_assoc($rev_result)) {
                    echo '<div class="col-md-4 text-start">
                            <div class="border p-3 h-100 rounded shadow-sm">
                                <div class="text-warning mb-2">' . str_repeat("⭐", $rev_row['rating']) . '</div>
                                <h6 class="fw-bold">' . htmlspecialchars($rev_row['user_name']) . '</h6>
                                <p class="text-muted small mb-0">"' . substr(htmlspecialchars($rev_row['comment']), 0, 80) . '..."</p>
                            </div>
                          </div>';
                }
            }
            ?>
        </div>

        <a href="all_reviews.php" class="btn btn-outline-dark px-4">See All Reviews</a>
    </div>
</section>

    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container text-center text-md-left">
            <div class="row">
                <div class="col-md-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Crafteholic</h5>
                    <p>Handmade with love. We work to make your special moments more colorful.</p>
                </div>
                <div class="col-md-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Products</h5>
                    <p><a href="index.php?category=Bouquet" class="text-white text-decoration-none">Bouquets</a></p>
                    <p><a href="index.php?category=Scrapbook" class="text-white text-decoration-none">Scrapbooks</a></p>
                </div>
                <div class="col-md-4 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Contact</h5>
                    <p>Khilgaon, Dhaka</p>
                    <p>info@crafteholic.com</p>
                    <p>+8801761443802</p>
                </div>
            </div>

            <hr class="mb-4 border-secondary">
            
            <div class="row align-items-center text-start">
                <div class="col-md-7">
                    <p class="mb-2 fw-bold text-warning" style="font-size: 0.9rem;">Crafteholic International</p>
                    <div class="d-flex gap-3 flex-wrap align-items-center">
                        <span class="small text-white-50"><img src="https://flagcdn.com/w20/bd.png" width="20" class="me-1"> Bangladesh</span>
                        <span class="small text-white-50"><img src="https://flagcdn.com/w20/pk.png" width="20" class="me-1"> Pakistan</span>
                        <span class="small text-white-50"><img src="https://flagcdn.com/w20/lk.png" width="20" class="me-1"> Sri Lanka</span>
                        <span class="small text-white-50"><img src="https://flagcdn.com/w20/mm.png" width="20" class="me-1"> Myanmar</span>
                        <span class="small text-white-50"><img src="https://flagcdn.com/w20/jp.png" width="20" class="me-1"> Japan</span>
                    </div>
                </div>
                <div class="col-md-5 text-md-end mt-4 mt-md-0">
                    <p class="mb-2 fw-bold text-warning" style="font-size: 0.9rem;">Follow Us</p>
                    <div class="d-flex justify-content-md-end gap-3">
                        <a href="https://www.facebook.com/share/1DhtM7Wwd5/" target="_blank" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.youtube.com/@miftahatelier" target="_blank" class="text-white fs-4"><i class="bi bi-youtube"></i></a>
                        <a href="https://www.instagram.com/craft_eholic?igsh=bHFhZXR1Mzd6aXpj" target="_blank" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>

            <hr class="mb-4 border-secondary">
            <p class="text-center mb-0">© 2026 Copyright: <strong>Crafteholic</strong>. All rights reserved.</p>
        </div>
    </footer>

    <button id="backToTop" class="btn btn-dark shadow-lg">↑</button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    
    <script>
        // Typing Effect
        const heroText = document.querySelector('.typing');
        if(heroText) {
            let text = heroText.innerText;
            heroText.innerText = "";
            let i = 0;
            function typeWriter() {
                if (i < text.length) {
                    heroText.innerText += text.charAt(i);
                    i++;
                    setTimeout(typeWriter, 50);
                }
            }
            window.onload = typeWriter;
        }

        // Back to Top Button
        const backToTopBtn = document.getElementById("backToTop");
        window.onscroll = function() {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                backToTopBtn.style.display = "block";
            } else {
                backToTopBtn.style.display = "none";
            }
        };
        backToTopBtn.onclick = function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        // Load More Logic
        $(document).ready(function(){
            var itemsToShow = 9; // প্রথমে ৯টি দেখাবে
            var itemsIncrement = 6; // প্রতি ক্লিকে ৩টি করে বাড়বে

            $(".product-item").hide();
            $(".product-item").slice(0, itemsToShow).show();

            // যদি মোট প্রোডাক্ট সংখ্যা ৯ এর কম হয় তবে বাটনটি দেখাবে না
            if ($(".product-item").length <= itemsToShow) {
                $("#loadMore").hide();
            }

            $("#loadMore").on("click", function(e){
                e.preventDefault();
                $(".product-item:hidden").slice(0, itemsIncrement).fadeIn();
                
                if($(".product-item:hidden").length == 0) {
                    $("#loadMore").fadeOut('slow');
                }
            });
        });
    </script>
</body>
</html>