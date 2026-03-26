<?php
session_start();

// ১. সেশনের সব ভেরিয়েবল খালি করা
$_SESSION = array();

// ২. যদি সেশন কুকি থাকে তবে সেটি মুছে ফেলা
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ৩. সেশন পুরোপুরি ধ্বংস করা
session_destroy();

// ৪. ক্যাশে ক্লিয়ার করার জন্য হেডার (ঐচ্ছিক কিন্তু ভালো)
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// ৫. হোম পেজে রিডাইরেক্ট
header("Location: index.php");
exit();
?>