<?php
// Check if a session is already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include your database connection code
include('config.php');

// Fetch all blog posts
$sql = "SELECT id, title FROM posts";
$result = $conn->query($sql);

// Fetch categories
$sqlCategories = "SELECT id, name FROM categories";
$resultCategories = $conn->query($sqlCategories);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Add your CSS styles here -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-image: url('dashboard.jpg'); /* Replace with the path to your background image */
            background-size: cover;
            background-position: center;
            color: #fff; /* White text color */
        }

        header {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background for header */
            color: #fff;
            text-align: center;
            padding: 1rem;
            margin-bottom: 20px;
        }

        h1 {
            color: #fff; /* White color for heading */
            font-size: 2rem;
            margin: 0;
        }

        h2 {
            color: #000; /* Black color for heading */
            font-size: 1.6rem;
            margin-top: 2rem;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 0.5rem;
            color: #000; /* Black color for blog post */
            font-size: 130%; /* Increase font size by 30% */
        }

        li a {
            text-decoration: none;
            color: #007bff; /* Blue color for 'View More' */
        }

        li a:hover {
            text-decoration: underline;
        }

        div.categories {
            display: flex;
            margin-bottom: 1rem;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
        }

        button:hover {
            background-color: #0056b3;
        }

        h2, h3 {
            border-bottom: none; /* Remove the border for headings */
            padding-bottom: 0; /* Remove padding bottom for headings */
        }

        button.actions {
            background-color: #28a745; /* Green button for actions */
        }

        button.actions:hover {
            background-color: #218838; /* Darker green on hover */
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome to the Dashboard!</h1>
    </header>

    <h2>Blog Posts</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['title']} <a href='view_post.php?id={$row['id']}'>View More</a></li>";
            }
        } else {
            echo "<li>No posts available</li>";
        }
        ?>
    </ul>

    <h2>Categories</h2>
    <div class="categories">
        <button style="background-color: #dc3545;"><a href="Personal_Blog.php">Personal Blog</a></button>
        <button style="background-color: #ffc107;"><a href="Business_Blog.php">Business Blog</a></button>
        <button style="background-color: #17a2b8;"><a href="Affiliate_Blog.php">Affiliate Blog</a></button>
        <button style="background-color: #28a745;"><a href="Niche_Blog.php">Niche Blog</a></button>
        <button style="background-color: #007bff;"><a href="News_Blog.php">News Blog</a></button>
    </div>

    <h2>Actions</h2>
    <button class="actions"><a href="create_post.php">Create Blog</a></button>
    <button class="actions"><a href="my_posts.php">My Posts</a></button>
    <button class="actions"><a href="logout.php">Logout</a></button>

</body>
</html>
