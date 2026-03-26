<?php
session_start();
include 'db_config.php';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>All Reviews - Crafteholic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">← Back to Home</a>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="text-center fw-bold mb-5">What People Say About Crafteholic</h2>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if(isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php 
                            if($_GET['msg'] == 'deleted') echo "রিভিউটি মুছে ফেলা হয়েছে!";
                            if($_GET['msg'] == 'updated') echo "রিভিউটি আপডেট করা হয়েছে!";
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php
                $all_rev = mysqli_query($conn, "SELECT * FROM reviews ORDER BY id DESC");
                if(mysqli_num_rows($all_rev) > 0) {
                    while($row = mysqli_fetch_assoc($all_rev)) {
                ?>
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($row['user_name']); ?></h6>
                                <p class="text-muted mb-1"><?php echo htmlspecialchars($row['comment']); ?></p>
                            </div>
                            <span class="text-warning"><?php echo str_repeat("⭐", $row['rating']); ?></span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted" style="font-size: 10px;"><?php echo $row['created_at']; ?></small>
                            
                            <?php if(isset($_SESSION['user_id']) && ($row['user_id'] == $_SESSION['user_id'] || $_SESSION['user_role'] == 'admin')): ?>
    <div class="mt-2 text-end">
        <a href="edit_review.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
        <a href="delete_review.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('নিশ্চিত?')">Delete</a>
    </div>
<?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<p class='text-center text-muted'>এখনো কোনো রিভিউ দেওয়া হয়নি।</p>";
                }
                ?>

                <?php if(isset($_SESSION['user_id'])): ?>
                <div class="card mt-5 p-4 border-0 shadow">
                    <h5 class="fw-bold mb-3">Leave a Review</h5>
                    <form action="post_review.php" method="POST">
                        <div class="mb-3">
                            <select class="form-select" name="rating" required>
                                <option value="5">5 Star - Excellent</option>
                                <option value="4">4 Star - Good</option>
                                <option value="3">3 Star - Average</option>
                                <option value="2">2 Star - Poor</option>
                                <option value="1">1 Star - Very Bad</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="comment" rows="3" placeholder="Share your experience..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Post Review</button>
                    </form>
                </div>
                <?php else: ?>
                    <p class="text-center mt-4 text-muted small">Please <a href="login.php" class="text-dark fw-bold">login</a> to leave a review.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>