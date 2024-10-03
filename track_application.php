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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="style(1).css" rel="stylesheet" />
    <style>
        h1 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
            color: #ECF0F1;
        }

        .mb-3 input {
            width: 300px;
            margin-left: 650px;
            margin-top: 140px;
        }

        button {
            background-color: #369481;
            color: #ECF0F1;
            font-weight: medium;
            margin-left: 850px;
            border: none;
            border-radius: 5px;
            padding: 5px;
        }

        button:hover {
            background-color: #1ABC9C;
        }

        img {
            width: 300px;
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <h1>Track Your Application</h1>
    <form method="POST">
        <img src="Images/school_tracking_icon_no_background.webp" alt="track-application icon" style="color: #ECF0F1;">
        <div class="mb-3">
            <input type="text" name="id_num" class="form-control" id="id_num" placeholder="Enter your ID number"
                required>
        </div>
        <button type="submit">Check Status</button>
    </form>
    <p><?= $status_message ?></p>
</body>

</html>