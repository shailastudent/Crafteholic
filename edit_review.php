<?php
session_start();
include 'db_config.php';

$id = $_GET['id'];
$u_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT * FROM reviews WHERE id='$id' AND user_id='$u_id'");
$data = mysqli_fetch_assoc($res);

if(isset($_POST['update'])){
    $new_comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $new_rating = $_POST['rating'];
    mysqli_query($conn, "UPDATE reviews SET comment='$new_comment', rating='$new_rating' WHERE id='$id'");
    header("Location: all_reviews.php?msg=updated");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card p-4 shadow-sm mx-auto" style="max-width: 500px;">
            <h4>Edit Your Review</h4>
            <form method="POST">
                <select name="rating" class="form-select mb-3">
                    <option value="5" <?php if($data['rating']==5) echo 'selected'; ?>>5 Star</option>
                    <option value="4" <?php if($data['rating']==4) echo 'selected'; ?>>4 Star</option>
                    <option value="3" <?php if($data['rating']==3) echo 'selected'; ?>>3 Star</option>
                </select>
                <textarea name="comment" class="form-control mb-3" rows="3"><?php echo $data['comment']; ?></textarea>
                <button name="update" class="btn btn-dark w-100">Update Review</button>
            </form>
        </div>
    </div>
</body>
</html>