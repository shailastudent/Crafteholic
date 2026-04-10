<?php
session_start();
include 'db_config.php';

// ১. চেক করা ইউজার লগইন আছে কি না
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$u_id = $_SESSION['user_id'];

// ২. URL থেকে ID ঠিকমতো আসছে কি না চেক করা
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // ৩. শুধুমাত্র ওই ইউজারের রিভিউটিই খুঁজে বের করা
    $res = mysqli_query($conn, "SELECT * FROM reviews WHERE id='$id' AND user_id='$u_id'");
    $data = mysqli_fetch_assoc($res);

    // যদি ডাটাবেজে এই ID-র কোনো রিভিউ না থাকে
    if (!$data) {
        die("<div class='alert alert-danger'>Review not found or you don't have permission to edit this.</div>");
    }
} else {
    die("<div class='alert alert-danger'>Invalid Request!</div>");
}

// ৪. আপডেট লজিক
if (isset($_POST['update'])) {
    $new_comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $new_rating = $_POST['rating'];
    
    $update_query = "UPDATE reviews SET comment='$new_comment', rating='$new_rating' WHERE id='$id' AND user_id='$u_id'";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: all_reviews.php?msg=updated");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card p-4 shadow-sm mx-auto" style="max-width: 500px;">
            <h4 class="mb-3 text-center">Edit Your Review</h4>
            <form method="POST">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select mb-3">
                    <option value="5" <?php if(isset($data['rating']) && $data['rating']==5) echo 'selected'; ?>>5 Star</option>
                    <option value="4" <?php if(isset($data['rating']) && $data['rating']==4) echo 'selected'; ?>>4 Star</option>
                    <option value="3" <?php if(isset($data['rating']) && $data['rating']==3) echo 'selected'; ?>>3 Star</option>
                    <option value="2" <?php if(isset($data['rating']) && $data['rating']==2) echo 'selected'; ?>>2 Star</option>
                    <option value="1" <?php if(isset($data['rating']) && $data['rating']==1) echo 'selected'; ?>>1 Star</option>
                </select>

                <label class="form-label">Comment</label>
                <textarea name="comment" class="form-control mb-3" rows="3" required><?php echo htmlspecialchars($data['comment'] ?? ''); ?></textarea>
                
                <button name="update" class="btn btn-dark w-100">Update Review</button>
                <a href="all_reviews.php" class="btn btn-link w-100 mt-2 text-decoration-none text-muted">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>