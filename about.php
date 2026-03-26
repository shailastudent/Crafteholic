<?php
session_start();
include 'db_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Crafteholic</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">

    <style>
        .about-header { background: #f8f9fa; padding: 80px 0; }
        .feature-icon { font-size: 2.5rem; color: #ffc107; }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php" style="font-family: 'Playfair Display';">
                Crafteholic
            </a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-dark btn-sm">Back to Home</a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="about-header text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Our Story</h1>
            <p class="lead text-muted">Spreading love through handmade crafts.</p>
        </div>
    </header>

    <!-- About Section -->
    <section class="container my-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
               <img src="image/logo.png" class="logo-image rounded" alt="Handmade Craft">
                    
            </div>

            <div class="col-lg-6 mt-4 mt-lg-0">
                <h2 class="fw-bold mb-3">Why We Are Unique?</h2>

                <p>
                    <strong>Crafteholic</strong> is not just a shop, it is an artistic passion. 
                    We believe every gift carries a beautiful story. 
                    Starting from Khilgaon, Dhaka, our journey is now bringing smiles to hundreds of people.
                </p>

                <p>
                    Our skilled artisans carefully handcraft every 
                    <strong>Bouquet</strong>, <strong>Scrapbook</strong>, and <strong>Hijab</strong> item. 
                    In this age of automation, we bring you the pure touch of handmade art.
                </p>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="bg-white py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-5">Our Features</h2>

            <div class="row g-4">
                
                <div class="col-md-4">
                    <div class="p-4 shadow-sm rounded border">
                        <div class="feature-icon mb-3">🎨</div>
                        <h4>100% Handmade</h4>
                        <p class="text-muted">Every product is crafted by experienced artisans.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 shadow-sm rounded border">
                        <div class="feature-icon mb-3">🎁</div>
                        <h4>Customized Gifts</h4>
                        <p class="text-muted">We personalize gifts according to your preferences.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 shadow-sm rounded border">
                        <div class="feature-icon mb-3">🚚</div>
                        <h4>Fast Delivery</h4>
                        <p class="text-muted">Safe and quick delivery to your doorstep.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container text-center">
            <p>© 2026 Copyright: <strong>Crafteholic</strong>. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>