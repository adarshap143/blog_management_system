<?php
session_start();
include('config.php'); // Include the database connection configuration

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the post ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to my_posts.php if post ID is not provided or invalid
    header("Location: my_posts.php");
    exit();
}

$postId = $_GET['id'];

// Fetch the post data from the database
$sql = "SELECT * FROM posts WHERE id = $postId AND user_id = {$_SESSION['user_id']}";
$result = mysqli_query($conn, $sql);

// Check if the post exists and belongs to the logged-in user
if (mysqli_num_rows($result) === 0) {
    // Redirect to my_posts.php if the post doesn't exist or doesn't belong to the user
    header("Location: my_posts.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Handle form submission for editing the post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_post'])) {
    // Validate and sanitize user inputs (you should add more validation)
    $title = htmlspecialchars($_POST['title']);
    $content = $_POST['content'];
    $tags = $_POST['tags'];

    // Update the post data in the database
    $sqlUpdatePost = "UPDATE posts SET title = ?, content = ?, tags = ? WHERE id = ?";
    $stmt = $conn->prepare($sqlUpdatePost);
    $stmt->bind_param("sssi", $title, $content, $tags, $postId);
    $stmt->execute();
    $stmt->close();

    // Redirect to my_posts.php after editing
    header("Location: my_posts.php");
    exit();
}

// Handle form submission for deleting the post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_post'])) {
    // Delete the post from the database
    $sqlDeletePost = "DELETE FROM posts WHERE id = $postId";
    mysqli_query($conn, $sqlDeletePost);

    // Redirect to my_posts.php after deleting
    header("Location: my_posts.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Edit Post - Blog Management System</title>
    

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
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-top: 1rem;
        font-weight: bold;
    }

    input,
    textarea {
        padding: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #007bff;
        color: #fff;
        padding: 0.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    p {
        margin-top: 1rem;
    }

    a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }
</style>

</head>
<body>

    <header>
        <h1>Blog Management System</h1>
    </header>

    <main>
        <section class="edit-post">
            <h2>Edit Post</h2>

            <form action="" method="post">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?= $row['title'] ?>" required>

                <label for="content">Content:</label>
                <textarea name="content" rows="4" required><?= $row['content'] ?></textarea>

                <label for="tags">Tags:</label>
                <input type="text" name="tags" value="<?= $row['tags'] ?>">

                <button type="submit" name="edit_post">Save Changes</button>
            </form>
<p></p>
            <form action="" method="post">
                <button type="submit" name="delete_post">Delete Post</button>
            </form>

            <p><a href="my_posts.php">Back to My Posts</a></p>
        </section>
    </main>

    <footer>
        <!-- Footer content goes here -->
    </footer>

</body>
</html>
