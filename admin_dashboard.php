<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_approval_db";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);


if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'])) {
    $status = $_POST['status'];
    $reason = $_POST['reason'] ?? null;

    $stmt = $con->prepare("UPDATE applications SET status=?, rejection_reason=? WHERE id=?");
    $stmt->bind_param("ssi", $status, $reason, $_POST['application_id']);
    $stmt->execute();
}

$result = $con->query("SELECT * FROM applications");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>

<body>
    <h1>Welcome,
        <?php echo $_SESSION['admin']; ?>!
    </h1>
    <h3>Applications</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['fullname'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
                        <select name="status">
                            <option value="accepted">Accept</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <textarea name="reason" placeholder="Rejection reason"></textarea>
                        <button type="submit">Submit</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="logout.php">Logout</a>
</body>

</html>