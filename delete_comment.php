<?php
session_start();
include('config.php'); // Include your database connection configuration

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the comment ID is set in the URL
if (isset($_GET['comment_id'])) {
    $commentId = $_GET['comment_id'];

    // Fetch the post ID associated with the comment
    $sqlGetPostId = "SELECT post_id FROM comments WHERE id = $commentId";
    $resultGetPostId = $conn->query($sqlGetPostId);

    if ($resultGetPostId->num_rows > 0) {
        $postId = $resultGetPostId->fetch_assoc()['post_id'];

        // Fetch the comment to check if it exists and belongs to the logged-in user
        $sqlCheckComment = "SELECT user_id FROM comments WHERE id = $commentId";
        $resultCheckComment = $conn->query($sqlCheckComment);

        if ($resultCheckComment->num_rows > 0) {
            $comment = $resultCheckComment->fetch_assoc();

            // Check if the comment belongs to the logged-in user
            if ($_SESSION['user_id'] == $comment['user_id']) {
                // Delete the comment
                $sqlDeleteComment = "DELETE FROM comments WHERE id = $commentId";
                if ($conn->query($sqlDeleteComment)) {
                    echo "Comment deleted successfully!";
                } else {
                    echo "Error deleting comment: " . $conn->error;
                }
            } else {
                echo "You do not have permission to delete this comment.";
            }
        } else {
            echo "Comment not found.";
        }
    } else {
        echo "Post ID associated with the comment not found.";
    }
} else {
    echo "Comment ID is missing.";
}

// Redirect back to the view_post.php page
header("Location: view_post.php?id={$postId}");
exit();
?>
