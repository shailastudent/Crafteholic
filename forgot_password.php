<?php
session_start();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .reset-card { width: 100%; max-width: 450px; border-radius: 15px; margin-top: 100px; }
        .btn-reset { background-color: #5d4037; border: none; color: white; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg border-0 p-4 reset-card">
            <div class="card-body">
                <h4 class="text-center mb-4" style="color: #5d4037;">Reset Your Password</h4>
                <p class="text-muted small text-center">আপনার রেজিস্টার্ড ইমেইলটি দিন, আমরা একটি রিসেট লিঙ্ক পাঠাবো।</p>
                
                <form action="forgot_password_process.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="example@mail.com" required>
                    </div>
                    <button type="submit" class="btn btn-reset w-100 py-2">Send Reset Link</button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none small text-secondary">← Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>