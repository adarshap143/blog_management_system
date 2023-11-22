<?php
session_start();
include('config.php');

// Fetch posts from the "Niche Blog" category
$sql = "SELECT posts.*, COUNT(likes.id) AS like_count, GROUP_CONCAT(comments.content) AS comments
        FROM posts
        LEFT JOIN likes ON posts.id = likes.post_id
        LEFT JOIN comments ON posts.id = comments.post_id
        WHERE posts.category_id = 4
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
    <title>Niche Blog - Blog Management System</title>
    <style>
        <!-- Add the following styles within the <style> tag in the <head> section of your HTML file -->

<style>
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
        margin: 0 auto; /* Center-align the content */
    }

    .blog-posts {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    article {
        margin-bottom: 20px;
        text-align: left;
    }

    img {
        max-width: 100%;
        height: auto;
        margin-top: 20px;
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
</style>

    </style>
</head>
<body>

    <header>
        <h1>Niche Blog</h1>
    </header>

    <main>
        <section class="blog-posts">
            <h2>Posts</h2>

            <?php
            // Check if there are posts in the category
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<article>";
                    echo "<h3>{$row['title']}</h3>";
                    echo "<p>{$row['content']}</p>";

                    // Display the image if available
                    if (!empty($row['images'])) {
                        echo "<img src='data:image/jpeg;base64," . base64_encode($row['images']) . "' alt='Blog Image'>";
                    }

                    echo "<p><strong>Tags:</strong> {$row['tags']}</p>";
                    echo "<p><strong>Like Count:</strong> {$row['like_count']}</p>";
                    echo "<p><strong>Comments:</strong> {$row['comments']}</p>";
                    echo "</article>";
                }
            } else {
                echo "<p>No posts found in this category.</p>";
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
