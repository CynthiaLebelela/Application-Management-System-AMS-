<?php
// Step 1: Database connection
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

// Step 2: Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $employee_id = mysqli_real_escape_string($con, $_POST['employee_id']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Step 3: Validate form input (e.g., password match, unique email, etc.)
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Step 4: Insert data into the database
    $sql = "INSERT INTO admins (fullname, dob, gender, employee_id, department, email, phone_number, username, password)
            VALUES ('$fullname', '$dob', '$gender', '$employee_id', '$department', '$email', '$phone_number', '$username', '$hashed_password')";

    if ($con->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

// Close the database connection
$con->close();
?>