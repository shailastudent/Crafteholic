<?php
session_start();
include 'db_config.php';
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <style>
        body { background-color: #f8f9fa; }
        .login-card { width: 100%; max-width: 400px; border-radius: 15px; }
        .btn-login { background-color: #5d4037; border: none; color: white; }
        .btn-login:hover { background-color: #3e2723; color: white; }
        .input-group-text { cursor: pointer; background-color: white; }
        .g-recaptcha { display: flex; justify-content: center; margin-bottom: 15px; }
        /* Forgot Password লিঙ্কের স্টাইল */
        .forgot-link { font-size: 0.85rem; color: #5d4037; text-decoration: none; font-weight: 500; }
        .forgot-link span { color: #9c27b0; } /* Password লেখাটি পার্পল কালার */
        .forgot-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg border-0 p-4 login-card">
            <div class="card-body">
                <h3 class="text-center mb-4" style="font-family: 'Playfair Display', serif; color: #5d4037;">Login to Crafteholic</h3>
                
                <form action="login_process.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="example@mail.com" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="******" required>
                            <span class="input-group-text" id="togglePassword">
                                <i class="fas fa-eye-slash" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="text-end mb-3">
                        <a href="forgot_password.php" class="forgot-link">
                            Forgot your <span>Password ?</span>
                        </a>
                    </div>

                    <div class="g-recaptcha" data-sitekey="6Ld7HpcsAAAAALNIgn9HzQjeZWX0q5JATImgsPV7"></div>

                    <button type="submit" class="btn btn-login w-100 py-2 mt-2">Login</button>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted">Don't have an account? <a href="register.php" class="text-decoration-none" style="color: #5d4037; font-weight: bold;">Sign Up</a></p>
                    <a href="index.php" class="small text-decoration-none text-secondary">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>

   <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>