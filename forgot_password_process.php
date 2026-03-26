<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // ইউজার আছে কি না চেক করা
    $user_check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($user_check) > 0) {
        // একটি ইউনিক টোকেন তৈরি করা
        $token = bin2hex(random_bytes(50));
        
        // ডাটাবেসে টোকেন আপডেট করা (আপনার টেবিল অনুযায়ী কলাম নাম দেখে নিন)
        mysqli_query($conn, "UPDATE users SET reset_token = '$token' WHERE email = '$email'");
        
        // এখানে ইমেইল পাঠানোর কোড থাকবে (PHPMailer হলে ভালো)
        // আপাতত লোকালহোস্টের জন্য আমরা একটি লিঙ্ক ইকো করছি
        $reset_link = "http://localhost/crafteholic/reset_password.php?token=" . $token;
        
        echo "<div style='text-align:center; margin-top:50px;'>";
        echo "<h3>লোকালহোস্ট টেস্ট লিঙ্ক:</h3>";
        echo "<p>বাস্তব সাইটে এটি ইউজারের ইমেইলে যাবে:</p>";
        echo "<a href='$reset_link'>$reset_link</a>";
        echo "</div>";
        
    } else {
        echo "<script>alert('এই ইমেইলটি আমাদের সিস্টেমে নেই!'); window.history.back();</script>";
    }
}
?>