<?php
session_start();
// Include your database connection code
include('config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields are set
    if (isset($_POST['create_post'], $_POST['title'], $_POST['content'], $_POST['category'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $tags = isset($_POST['tags']) ? $_POST['tags'] : "";

        // Check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            // Handle image upload
            $imageData = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $imageData = file_get_contents($_FILES['image']['tmp_name']);
            }

            // Insert the post data into the posts table
            $sqlCreatePost = "INSERT INTO posts (title, content, images, category_id, tags, user_id)
                              VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sqlCreatePost);
            $stmt->bind_param("sssssi", $title, $content, $imageData, $category, $tags, $userId);

            if ($stmt->execute()) {
                echo "Post created successfully!";
            } else {
                echo "Error creating post: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "User not logged in.";
        }
    } else {
        echo "Required fields are missing.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('create_blog.jpg') center/cover no-repeat;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h1 {
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 20px;
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0;
            color: #333;
        }

        input,
        textarea,
        select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link-container {
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Create a New Blog Post</h1>

    <form action="create_post.php" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" required>

        <label for="content">Content:</label>
        <textarea name="content" rows="4" required></textarea>

        <label for="category">Category:</label>
        <select name="category" required>
            <!-- Populate with categories from the database -->
            <?php
            $sqlCategories = "SELECT id, name FROM categories";
            $resultCategories = $conn->query($sqlCategories);

            while ($row = $resultCategories->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>

        <label for="tags">Tags:</label>
        <input type="text" name="tags">

        <label for="image">Image (Optional):</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="create_post">Create Post</button>
    </form>

    <div class="back-link-container">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>

</body>
</html>
