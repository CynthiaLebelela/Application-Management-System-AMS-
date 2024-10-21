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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="style(1).css" rel="stylesheet" />
    <script src="script.js"></script>
    <title>Admin Dashboard</title>
    <style>
        button {
            margin-left: 1250px;
            margin-top: -180px;
            padding: 8px;
            border-radius: 5px;
            border: NONE;
            background-color: #369481;
            color: #ECF0F1;
        }

        button:hover {
            color: #d0eff7;
            background-color: #1ABC9C;
        }

        h1,
        h3,
        th {
            color: #ECF0F1;
        }

        table {
            border-collapse: collapse;
            margin-left: 80px;
            font-size: 18px;
            min-width: 600px;
            background-color: #ECF0F1;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        table th,
        table td {
            border: 1px solid #d0eff7;
            padding: 12px 15px;
            text-align: left;
        }

        table th {
            background-color: #1ABC9C;
            color: white;
        }

        table tr {
            background-color: #ECF0F1;
        }

        table tr:nth-child(even) {
            background-color: #d0eff7;
        }

        table tr:hover {
            background-color: #369481;
            color: white;
        }

        table tr:nth-child(even):hover {
            background-color: #1ABC9C;
            color: white;
        }

        .sub {
            margin-left: 50px;
        }

        h3 {
            margin-left: 40px;
        }

        a {
            margin-left: 15px;
            text-decoration: none;
            color: #d0eff7;
            font-size: 20px;
            font-weight: bold;
        }

        a:hover {
            color: #ECF0F1;
        }
    </style>
</head>

<body class="light">
    <h1>Welcome,
        <?php echo $_SESSION['admin']; ?>!
    </h1>
    <button class="changeTheme" onclick="changeTheme();">Light Mode</button>
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
                            <option value="action">Select Action</option>
                            <option value="pending">Pending</option>
                            <option value="accepted">Accept</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <textarea name="reason" placeholder="Rejection reason"></textarea>
                        <button class="sub" type="submit">Submit</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="logout.php">Logout</a>
</body>

</html>