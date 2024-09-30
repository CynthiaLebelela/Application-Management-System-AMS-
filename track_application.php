<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_approval_db";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$status_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_num'])) {
    $id_num = $_POST['id_num'];
    $stmt = $con->prepare("SELECT status, rejection_reason FROM applications WHERE id_num = ?");
    $stmt->bind_param("s", $id_num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $application = $result->fetch_assoc();
        $status_message = "Your application status is: " . $application['status'];
        if ($application['status'] == 'rejected') {
            $status_message .= " (Reason: " . $application['rejection_reason'] . ")";
        }
    } else {
        $status_message = "No application found with that ID number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Track Application</title>
</head>

<body>
    <h1>Track Your Application</h1>
    <form method="POST">
        <input type="text" name="id_num" placeholder="Enter your ID number" required>
        <button type="submit">Check Status</button>
    </form>
    <p><?= $status_message ?></p>
</body>

</html>