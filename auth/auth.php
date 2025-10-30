<?php
session_start();

// Redirect to index if already logged in
if (isset($_SESSION['username'])) {
    header('Location: /index.php');
    exit();
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'control_server');
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $formType = $_POST['formType'];

    if ($formType === 'signup') {
        // Handle Signup
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $username, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header('Location: /index.php');
            exit();
        } else {
            echo "<p style='color:red;'>Error: Username already exists.</p>";
        }
    } elseif ($formType === 'login') {
        // Handle Login
        $query = "SELECT password FROM users WHERE username = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($storedPassword);
        $stmt->fetch();

        if ($storedPassword && password_verify($password, $storedPassword)) {
            $_SESSION['username'] = $username;
            header('Location: /index.php');
            exit();
        } else {
            echo "<p style='color:red;'>Error: Invalid username or password.</p>";
        }
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f8ff;
            padding: 50px;
        }
        .form-container {
            display: inline-block;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        input {
            margin: 10px 0;
            padding: 10px;
            width: 80%;
        }
        button {
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Control Server</h1>
    <div class="form-container">
        <form method="POST">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="formType" value="login">
            <button type="submit">Login</button>
        </form>
    </div>
    <br>
    <div class="form-container">
        <form method="POST">
            <h2>Signup</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="formType" value="signup">
            <button type="submit">Signup</button>
        </form>
    </div>
</body>
</html>
