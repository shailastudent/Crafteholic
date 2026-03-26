<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow border-0 p-4" style="width: 100%; max-width: 450px; border-radius: 15px;">
            <h3 class="text-center mb-4">Create Account</h3>
            <form action="register_process.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-dark w-100 py-2">Sign Up</button>
            </form>
            <p class="text-center mt-3 small">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>