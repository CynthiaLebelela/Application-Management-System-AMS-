<?php
session_start();

// Database connection parameters
$servername = "localhost";  // Assuming you're running XAMPP locally
$db_username = "root";       // Default XAMPP MySQL username
$db_password = "";           // Default XAMPP MySQL password (blank)
$dbname = "school_approval_db"; // Your database name

// Create a connection
$con = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL Injection by using prepared statements
    $stmt = $con->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username); // Bind the username parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify password (Assuming the password is hashed using password_hash)
        if (password_verify($password, $user['password'])) {
            // Set session for logged-in user
            $_SESSION['admin'] = $user['username'];
            // Redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Invalid username or password!');</script>";
        }
    } else {
        // Username does not exist
        echo "<script>alert('Invalid username or password!');</script>";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$con->close();
