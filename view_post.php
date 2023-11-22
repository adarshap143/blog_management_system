<?php
session_start();

// Include your database connection code (config.php)
include('config.php');

// Fetch the post ID from the URL
$postId = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch the blog post based on the ID
$sqlPost = "SELECT id, title, content, tags, images FROM posts WHERE id = ?";
$stmtPost = $conn->prepare($sqlPost);

// Fetch comments for the post using a prepared statement
$sqlComments = "SELECT id, user_id, content, created_at FROM comments WHERE post_id = ?";
$stmtComments = $conn->prepare($sqlComments);

// Fetch likes for the post using a prepared statement
$sqlLikes = "SELECT * FROM likes WHERE post_id = ?";
$stmtLikes = $conn->prepare($sqlLikes);

// Bind the parameter and execute the statement only if $postId is not empty
if (!empty($postId)) {
    $stmtPost->bind_param("i", $postId);
    $stmtPost->execute();
    $resultPost = $stmtPost->get_result();

    // Check for errors
    if (!$resultPost) {
        die("Error in SQL query for post: " . $stmtPost->error);
    }

    $post = $resultPost->fetch_assoc();

    $title = isset($post['title']) ? $post['title'] : "Post Not Found";
    $content = isset($post['content']) ? $post['content'] : "The requested blog post does not exist.";
    $tags = isset($post['tags']) ? $post['tags'] : "";
    $images = isset($post['images']) ? $post['images'] : "";

    // Execute the comments statement only if $postId is not empty
    $stmtComments->bind_param("i", $postId);
    $stmtComments->execute();
    $resultComments = $stmtComments->get_result();

    // Check for errors
    if (!$resultComments) {
        die("Error in SQL query for comments: " . $stmtComments->error);
    }

    // Execute the likes statement only if $postId is not empty
    $stmtLikes->bind_param("i", $postId);
    $stmtLikes->execute();
    $resultLikes = $stmtLikes->get_result();

    // Check for errors
    if (!$resultLikes) {
        die("Error in SQL query for likes: " . $stmtLikes->error);
    }
} else {
    // Handle the case where $postId is empty (no blog post ID provided)
    $title = "Post Not Found";
    $content = "The requested blog post ID is missing.";
    $tags = "";
    $images = "";
    $resultComments = false;
    $resultLikes = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-align: center;
    }

    h1 {
        color: #007bff;
        margin-top: 20px;
    }

    .post-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 60%;
        margin: 20px auto;
        text-align: justify;
    }

    img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }

    .comments-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        max-width: 60%;
    }

    .comment {
        margin: 20px 0;
        text-align: left;
    }

    .comment-actions {
        margin-top: 10px;
    }

    /* Font color changed to black */
    button {
        background-color: #007bff;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 10px;
    }

    /* Hover color adjusted */
    button:hover {
        background-color: #0056b3;
    }

    form {
        margin-top: 20px;
    }

    textarea {
        width: 60%;
        margin: 10px auto;
        display: block;
    }

    a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }

    /* Adjusted font color */
    button a {
        color: #333;
        text-decoration: none;
    }

    /* Adjusted font color */
    h2, p {
        color: #333;
    }
</style>
</head>
<body>

    <h1><?php echo $title; ?></h1>

    <div class="post-container">
        <?php echo $content; ?>
        <p><strong>Tags:</strong> <?php echo $tags; ?></p>
        <?php
        if (!empty($images)) {
            echo "<img src='data:image/jpeg;base64," . base64_encode($images) . "' alt='Blog Image'>";
        }
        ?>
    </div>

    <div class="comments-container">
        <h2>Comments</h2>
        <?php
        if ($resultComments->num_rows > 0) {
            while ($comment = $resultComments->fetch_assoc()) {
                echo "<div class='comment'>";
                echo "<p>";
                echo "<strong>User:</strong> {$comment['user_id']}<br>";
                echo "<strong>Comment:</strong> {$comment['content']}<br>";
                echo "<strong>Posted at:</strong> {$comment['created_at']}<br>";

                // Display delete link for the user's own comment
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']) {
                    echo "<span class='comment-actions'>";
                    echo "<button><a href='delete_comment.php?comment_id={$comment['id']}'>Delete Comment</a></button>";
                    echo "</span>";
                }

                echo "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No comments yet.</p>";
        }
        ?>
        <h2>Actions</h2>
<?php
// Check if the user has already liked the post
$sqlCheckLike = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
$stmtCheckLike = $conn->prepare($sqlCheckLike);
$stmtCheckLike->bind_param("ii", $_SESSION['user_id'], $postId);
$stmtCheckLike->execute();
$resultCheckLike = $stmtCheckLike->get_result();

if ($resultCheckLike->num_rows > 0) {
    // User has already liked the post, show "Remove Like" button
    echo "<button><a href='remove_like.php?id={$postId}'>Remove Like</a></button>";
} else {
    // User has not yet liked the post, show "Like" button
    echo "<button><a href='like_post.php?id={$postId}'>Like</a></button>";
}
?>
<h2>Write a Comment</h2>
<form action="comment_post.php" method="post">
    <textarea name="comment" rows="4" required></textarea>
    <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
    <button type="submit" name="post_comment">Post Comment</button>
</form>

   </div>

<div>
    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
