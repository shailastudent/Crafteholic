<?php
include 'db_config.php'; // ডাটাবেস কানেকশন নিয়ে আসলাম

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // পাসওয়ার্ড এনক্রিপ্ট করা হলো

    // ডাটাবেসে তথ্য সেভ করার কোড
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration Successful!'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>