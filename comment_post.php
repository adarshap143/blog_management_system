<?php
session_start();
// Include your database connection code
include('config.php');

// Check if the form is submitted and the required fields are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_comment'], $_POST['comment'], $_POST['post_id'])) {
    $commentContent = $_POST['comment'];
    $postId = $_POST['post_id'];

    // Check if the user is logged in (you might want to implement a more secure check)
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Insert the comment into the comments table
        $sqlCommentPost = "INSERT INTO comments (user_id, post_id, content) VALUES ($userId, $postId, '$commentContent')";
        $conn->query($sqlCommentPost);
        echo "Comment posted successfully!";
    } else {
        echo "User not logged in.";
    }
} else {
    echo "Invalid request.";
}

// Redirect back to the view_post.php page
header("Location: view_post.php?id=$postId");
exit();
?>
