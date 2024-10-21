<?php
// Include PHPMailer
require 'PHPMailer/src/PHPMailer.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
    $mail->SMTPAuth = true;             // Enable SMTP authentication
    $mail->Username = 'your-email@gmail.com';   // SMTP username (Gmail address)
    $mail->Password = 'your-email-password';    // SMTP password (Gmail password or App password)
    $mail->SMTPSecure = 'tls';            // Enable TLS encryption, 'ssl' also accepted
    $mail->Port = 587;              // TCP port to connect to

    // Recipients
    $mail->setFrom('your-email@gmail.com', 'School Approval System');
    $mail->addAddress($email);     // Add a recipient (the user’s email)

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Password Reset Request';
    $mail->Body = 'Click on this link to reset your password: <a href="your-reset-link">Reset Password</a>';

    // Send email
    if ($mail->send()) {
        echo 'Email has been sent';
    } else {
        echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    $email = $_POST['email'];

    // Check if email exists in the user database
    $stmt = $con->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Store the token in the password_resets table
        $stmt = $con->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->bind_param('ss', $email, $token);
        $stmt->execute();

        // Send reset link to user's email
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click on this link to reset your password: " . $reset_link;
        $headers = 'From: no-reply@school_approval_system.com';
        mail($email, $subject, $message, $headers);

        echo "A password reset link has been sent to your email.";
    } else {
        echo "No account found with this email.";
    }

    $stmt->close();
    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>School Approval System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="style(1).css" rel="stylesheet" />
    <script src="script.js"></script>
    <style>
        h1 {
            color: #ECF0F1;
            padding: 10px;
            margin-top: 80px;
        }

        .form-group {
            margin-top: 105px;
        }

        .form-group label {
            color: #ECF0F1;
            font-weight: bold;
            font-size: 22px;
        }

        .form-group input {
            width: 300px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #369481;
            border: none;
        }

        .btn:hover {
            background-color: #1ABC9C;
        }
    </style>
</head>

<body>
    <center>
        <h1>Reset Password</h1>
        <form action="forgot_password.php" method="POST">
            <div class="form-group">
                <label for="email">Enter your email:</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email address" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </center>
</body>

</html>