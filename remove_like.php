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

        // Check if the user has liked the post
        $sqlCheckLike = "SELECT * FROM likes WHERE user_id = $userId AND post_id = $postId";
        $resultCheckLike = $conn->query($sqlCheckLike);

        if ($resultCheckLike->num_rows > 0) {
            // If the user has liked the post, remove the like
            $sqlRemoveLike = "DELETE FROM likes WHERE user_id = $userId AND post_id = $postId";
            $conn->query($sqlRemoveLike);
            echo "Like removed successfully!";
        } else {
            echo "You haven't liked this post.";
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
