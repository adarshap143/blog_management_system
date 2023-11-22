<?php
session_start();
include('config.php'); // Include the database connection configuration

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the user's posts from the database
$userId = $_SESSION['user_id'];
$sql = "SELECT posts.id, posts.title, posts.content, posts.images, posts.tags, posts.created_at,
               COUNT(likes.id) AS like_count,
               COUNT(comments.id) AS comment_count,
               GROUP_CONCAT(DISTINCT users.username ORDER BY likes.created_at ASC SEPARATOR ', ') AS liked_users,
               GROUP_CONCAT(DISTINCT CONCAT(users.username, ': ', comments.content) ORDER BY comments.created_at ASC SEPARATOR '<br>') AS comments_with_user
        FROM posts
        LEFT JOIN likes ON posts.id = likes.post_id
        LEFT JOIN comments ON posts.id = comments.post_id
        LEFT JOIN users ON likes.user_id = users.id
        WHERE posts.user_id = $userId
        GROUP BY posts.id
        ORDER BY posts.created_at DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>My Posts - Blog Management System</title>
   <!-- ... Previous HTML code ... -->

<style>
    /* Add your CSS styles here */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f0f0f0;
        color: #000;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    header {
        text-align: center;
        color: #fff;
        background-color: #333;
        padding: 1rem;
        width: 100%;
    }

    h1, h2, h3 {
        color: #000;
    }

    main {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        width: 80%;
        margin-top: 20px;
    }

    img {
        max-width: 100%;
        height: auto;
        margin-top: 20px;
    }

    article {
        margin-bottom: 20px;
        text-align: left; /* Align text to the left */
        position: relative; /* Add position relative */
    }

    .cutout {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 20px; /* Adjust the height of the cutout */
        background-color: #fff; /* Match the background color of the container */
        border-bottom-left-radius: 8px; /* Adjust border radius */
        border-bottom-right-radius: 8px; /* Adjust border radius */
    }

    a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }

    p {
        margin-top: 1rem;
    }

    section {
        display: flex;
        flex-direction: column;
    }

    /* Add more styles as needed */
</style>

<!-- ... Rest of the HTML code ... -->

</head>
<body>

    <header>
        <h1>Blog Management System</h1>
    </header>

    <main>
        <section class="my-posts">
            <h2>My Posts</h2>

            <?php
            // Check if the user has any posts
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<article>";
                    echo "<h3>{$row['title']}</h3>";
                    echo "<p>{$row['content']}</p>";
                    echo "<p><strong>Tags:</strong> {$row['tags']}</p>";
                    echo "<p><strong>Posted at:</strong> {$row['created_at']}</p>";

                    // Display the image if available
                    if (!empty($row['images'])) {
                        echo "<img src='data:image/jpeg;base64," . base64_encode($row['images']) . "' alt='Blog Image'>";
                    }

                    echo "<p><strong>Likes:</strong> {$row['like_count']} by {$row['liked_users']}</p>";
                    echo "<p><strong>Comments:</strong></p>";
                    echo "<ul>"; // Start the list

                    // Display each comment as a list item
                    echo "<li>" . str_replace(',', '</li><li>', $row['comments_with_user']) . "</li>";

                    echo "</ul>"; // End the list
                    echo "<div class='cutout'></div>"; // Add the cutout
                    echo "<p><a href='edit_post.php?id={$row['id']}'>Edit Post</a></p>";
                    echo "</article>";
                }
            } else {
                echo "<p>No posts found.</p>";
            }
            ?>

            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </section>
    </main>

    <footer>
        <!-- Footer content goes here -->
    </footer>

</body>
</html>
