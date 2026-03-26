<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass === $confirm_pass) {
        // নতুন পাসওয়ার্ড হ্যাশ করা
        $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);

        // পাসওয়ার্ড আপডেট করা এবং টোকেন ক্লিয়ার করা
        $update_sql = "UPDATE users SET password = '$hashed_password', reset_token = NULL WHERE reset_token = '$token'";
        
        if (mysqli_query($conn, $update_sql)) {
            echo "<script>alert('Password Updated Successfully!'); window.location='login.php';</script>";
        } else {
            echo "Error updating password: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    }
}
?>