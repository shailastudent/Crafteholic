<?php
session_start();
include 'db_config.php';

// নিশ্চিত করা হচ্ছে যে ইউজার লগইন করা আছে এবং ডাটা পোস্ট হয়েছে
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    
    // সেশন থেকে ডাটা নেওয়া
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name']; 
    
    // ফর্ম থেকে ডাটা নেওয়া এবং সিকিউর করা
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // ডাটাবেসে user_id সহ ইনসার্ট করা
    $sql = "INSERT INTO reviews (user_id, user_name, rating, comment) VALUES ('$user_id', '$user_name', '$rating', '$comment')";

    if (mysqli_query($conn, $sql)) {
        // সফল হলে রিভিউ পেজে পাঠিয়ে দেওয়া
        header("Location: all_reviews.php?msg=Review success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // লগইন না থাকলে লগইন পেজে পাঠিয়ে দেওয়া
    header("Location: login.php");
}
?>