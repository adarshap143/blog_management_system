<?php
session_start();
include('config.php'); // Include the database connection configuration

// Handle login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user inputs (you should add more validation)
    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];

    // Retrieve user data from the database based on the username
    // You should handle database connections and queries appropriately

    // Example query (you should use prepared statements to prevent SQL injection)
    $sql = "SELECT id, username, password FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    // Check if a user with the given username exists
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Check if the provided password matches the stored password in the database
        if ($password === $user["password"]) {
            // Start a session and store user information
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            // Redirect to the user's dashboard or another page
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p class='error'>Invalid password.</p>";
        }
    } else {
        echo "<p class='error'>User not found.</p>";
    }
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
            background-image: url('login.jpg'); /* Replace with the path to your background image */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: rgba(0, 0, 0, 0.7); /* Glassy effect */
            color: #fff;
            text-align: center;
            padding: 1rem;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            background-color: rgba(255, 255, 255, 0.8); /* Glassy effect */
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 0.5rem;
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

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #333;
            color: #fff;
        }

        /* Premium Style */
        .premium {
            background-color: #ffc107;
            color: #333;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Executive Style */
        .executive {
            background-color: #343a40;
            color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .executive header {
            background-color: #343a40;
        }

        .executive button {
            background-color: #28a745;
        }

        .executive button:hover {
            background-color: #218838;
        }

        .executive a {
            color: #17a2b8;
        }

        .executive a:hover {
            color: #138496;
        }

    </style>
    <title>Login - Blog Management System</title>
</head>
<body class="premium">

    <header>
        <h1>Blog Management System</h1>
    </header>

    <main>
        <section class="login-form">
            <h2>Login</h2>

            <form action="" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>

            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </section>
    </main>

    <footer>
        <!-- Footer content goes here -->
    </footer>

</body>
</html>
