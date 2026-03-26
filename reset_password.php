<?php
include 'db_config.php';

// লিঙ্ক থেকে টোকেনটি নেওয়া
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // টোকেনটি ডাটাবেসে আছে কি না চেক করা
    $user_res = mysqli_query($conn, "SELECT * FROM users WHERE reset_token = '$token'");
    
    if (mysqli_num_rows($user_res) == 0) {
        die("Invalid or Expired Token!");
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .reset-card { width: 100%; max-width: 400px; border-radius: 15px; margin-top: 80px; }
        .btn-update { background-color: #5d4037; border: none; color: white; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg border-0 p-4 reset-card">
            <div class="card-body">
                <h4 class="text-center mb-4" style="color: #5d4037;">Set New Password</h4>
                
                <form action="update_password.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="******" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="******" required>
                    </div>
                    
                    <button type="submit" class="btn btn-update w-100 py-2">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>