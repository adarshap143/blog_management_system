<?php
session_start();
include('config.php'); // Include the database connection configuration

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user inputs (you should add more validation)
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];

    // Perform the database insert (you should handle database connections appropriately)
    // Perform the database insert
$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

if (mysqli_query($conn, $sql)) {
    // Success
    header("Location: login.php");
    exit();
} else {
    // Error
    echo "Error: " . mysqli_error($conn);
}

    // Execute the SQL query and handle success or failure

    // Redirect to login page after successful signup
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('signup.jpg'); /* Replace with the path to your background image */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        header {
            text-align: center;
            color: #fff;
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.5rem;
        }

        main {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

        .signup-form h2 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 0.5rem 0;
            text-align: left;
            color: #333;
        }

        input {
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
            color: #333;
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
    <title>Signup - Blog Management System</title>
</head>
<body>
    <header>
        <h1>BLOG MANAGEMENT SYSTEM</h1>
    </header>

    <main>
        <section class="signup-form">
            <h2>Signup</h2>

            <form action="" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Signup</button>
            </form>
            <p>Have an account? <a href="login.php">Log in</a></p>
        </section>
    </main>

    <footer>
        <!-- Footer content goes here -->
    </footer>

</body>
</html>
