<?php
session_start();
include 'db_config.php';

if(isset($_GET['id']) && isset($_SESSION['user_id'])){
    $id = $_GET['id'];
    $u_id = $_SESSION['user_id'];
    
    // নিশ্চিত হওয়া যে ইউজার নিজের রিভিউ ডিলিট করছে
    mysqli_query($conn, "DELETE FROM reviews WHERE id='$id' AND user_id='$u_id'");
    header("Location: all_reviews.php?msg=deleted");
}
?>