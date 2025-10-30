<?php  
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: ./auth/login.php');
    exit();
}

if (isset($_GET['client']) && !empty($_GET['client'])) {
    $client = $_GET['client'];

    $db = new mysqli('localhost', 'root', '', 'control_server');
    if (mysqli_connect_errno()) exit;

    $query = "DELETE FROM clients WHERE name=?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $client); 
    $stmt->execute();
    $db->close();
    header("Location: /index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Control Server Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
            text-align: center;
        }

        .container {
            padding: 20px;
        }

        h2 {
            color: #444;
        }

        .table-container {
            margin: 20px auto;
            overflow-x: auto;
            width: 90%;
            max-width: 800px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        a:hover {
            color: #388E3C;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
        }

        .header img {
            width: 50px;
            vertical-align: middle;
        }

        .header h1 {
            display: inline;
            font-size: 1.8em;
            vertical-align: middle;
        }

        .footer {
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
        }

        .button {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #388E3C;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="serverIcon.png" alt="Server Icon">
        <h1>Control Server Dashboard</h1>
    </div>

    <div class="container">
        <h2>Client Management</h2>
        <p>Connected to the Command and Control Server</p>

        <div class="table-container">
            <?php
            $db = new mysqli('localhost', 'root', '', 'control_server');
            if (mysqli_connect_errno()) exit;

            $query = "SELECT id, name, ip FROM clients";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stmt->bind_result($id, $name, $ip);

            echo '<table>';
            echo '<thead><tr><th>ID</th><th>Name</th><th>IP</th><th>Action</th><th>Delete</th></tr></thead>';
            echo '<tbody>';
            while ($stmt->fetch()) {
                echo '<tr>';
                echo '<td>' . $id . '</td>';
                echo '<td>' . $name . '</td>';
                echo '<td>' . $ip . '</td>';
                echo '<td><a href="/openClient.php?client=' . $name . '">Administer</a></td>';
                echo '<td><a href="./index.php?client=' . $name . '" onclick="return confirm(\'Are you sure you want to delete this client?\')">Delete</a></td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            ?>
        </div>

        <button class="button" onclick="window.location.href='./auth/logout.php'">Logout</button>
    </div>

    <div class="footer">
        <p>&copy; 2024 Control Server. All rights reserved.</p>
    </div>
</body>
</html>
