<?php
session_start();
// Include your database connection code
include('config.php');

// Check if the post ID is set in the URL
if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // Check if the user is logged in (you might want to implement a more secure check)
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Check if the user has already liked the post
        $sqlCheckLike = "SELECT * FROM likes WHERE user_id = $userId AND post_id = $postId";
        $resultCheckLike = $conn->query($sqlCheckLike);

        if ($resultCheckLike->num_rows === 0) {
            // If the user hasn't liked the post yet, insert a new like
            $sqlLikePost = "INSERT INTO likes (user_id, post_id) VALUES ($userId, $postId)";
            $conn->query($sqlLikePost);
            echo "Post liked successfully!";
        } else {
            echo "You have already liked this post.";
        }
    } else {
        echo "User not logged in.";
    }
} else {
    echo "Post ID not provided.";
}

// Redirect back to the view_post.php page
header("Location: view_post.php?id=$postId");
exit();
?>
