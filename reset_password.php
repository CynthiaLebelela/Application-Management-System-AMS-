<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token is valid
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "school_approval_db";
    // Create connection
    $con = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $stmt = $con->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Token is valid, show the password reset form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $row = $result->fetch_assoc();
            $email = $row['email'];

            // Update the password in the users table
            $stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param('ss', $new_password, $email);
            $stmt->execute();

            // Delete the password reset token after successful reset
            $stmt = $con->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->bind_param('s', $token);
            $stmt->execute();

            echo "Your password has been updated successfully!";
        }
    } else {
        echo "Invalid or expired token.";
    }
}
?>

<!-- Password Reset Form -->
<form action="" method="POST">
    <div class="form-group">
        <label for="password">Enter new password:</label>
        <input type="password" class="form-control" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Reset Password</button>
</form>