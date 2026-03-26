<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // যদি কার্ট আগে থেকে না থাকে, তবে নতুন কার্ট তৈরি করা
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // কার্টে প্রোডাক্ট যোগ করা
    if (!in_array($product_id, $_SESSION['cart'])) {
        array_push($_SESSION['cart'], $product_id);
        // কাস্টমারকে আগের পেজেই ফেরত পাঠানো (Redirect back)
        echo "<script>alert('Product added to cart!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Product is already in your cart!'); window.history.back();</script>";
    }
}
?>