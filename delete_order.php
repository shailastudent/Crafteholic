<?php
session_start();
include 'db_config.php';

// শুধুমাত্র অ্যাডমিন ডিলিট করতে পারবে
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // ডাটাবেস থেকে অর্ডারটি ডিলিট করার কুয়েরি
    $delete_query = "DELETE FROM orders WHERE id = $order_id";

    if (mysqli_query($conn, $delete_query)) {
        // ডিলিট সফল হলে মেসেজসহ ফেরত যাবে
        header("Location: view_orders.php?msg=Order Deleted Successfully");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: view_orders.php");
}
?>