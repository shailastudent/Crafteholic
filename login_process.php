<?php
session_start();
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ১. reCAPTCHA যাচাই করা
    $recaptcha_secret = "6Ld7HpcsAAAAAMjzSiOsgWvjwQozzVjLfLdxs4bn"; // আপনার Secret Key এখানে বসান
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // গুগলের এপিআই-তে রিকোয়েস্ট পাঠানো
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response
    );

    $options = array(
        'http' => array (
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success = json_decode($verify);

    if ($captcha_success->success) {
        // ক্যাপচা সফল হলে আপনার আগের লগইন লজিক শুরু হবে
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // ডাটাবেস থেকে ওই ইমেইলের ইউজারকে খুঁজে বের করা
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // পাসওয়ার্ড যাচাই করা
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role']; // আপনার ডাটাবেসে কলামের নাম 'role' নাকি 'user_role' দেখে নিন

                echo "<script>alert('Login Successful!'); window.location='index.php';</script>";
            } else {
                echo "<script>alert('Wrong Password!'); window.location='login.php';</script>";
            }
        } else {
            echo "<script>alert('User not found!'); window.location='login.php';</script>";
        }
    } else {
        // ক্যাপচা পূরণ না করলে বা ভুল হলে
        echo "<script>alert('Please complete the reCAPTCHA to prove you are not a robot!'); window.location='login.php';</script>";
    }
}
?>