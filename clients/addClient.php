<?php
// include("../config/config.php");

// $name = $_POST["name"];
// $ip = $_POST["ip"];
// $query = "INSERT INTO clients(name, ip) VALUES(?,?)";
// $stmt = $db->prepare($query);
// $stmt->bind_param('ss',$name,$ip);
// $stmt->execute();
// $db->close();


include("../config/config.php");

$name = $_POST["name"];
$ip = $_POST["ip"];
$cmd = '';  // Set a default value for cmd (empty string or NULL)

// Prepare the SQL query to insert the client, including cmd
$query = "INSERT INTO clients (name, ip, cmd) VALUES (?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->bind_param('sss', $name, $ip, $cmd);  // Binding parameters (name, ip, cmd)
$stmt->execute();
$db->close();
?>




// include("../config/config.php");

// // Debugging: Check the incoming POST data
// error_log(print_r($_POST, true));  // Log the POST data to the error log

// if (isset($_POST["name"]) && isset($_POST["ip"])) {
//     $name = $_POST["name"];
//     $ip = $_POST["ip"];

//     // Prepare the SQL query
//     $query = "INSERT INTO clients (name, ip) VALUES (?, ?)";
//     if ($stmt = $db->prepare($query)) {
//         $stmt->bind_param("ss", $name, $ip);

//         // Execute the statement
//         if ($stmt->execute()) {
//             echo "Client added successfully!";
//         } else {
//             echo "Error executing query: " . $stmt->error;
//         }
//         $stmt->close();
//     } else {
//         echo "Error preparing query: " . $db->error;
//     }
// } else {
//     // If 'name' or 'ip' are missing, log the error
//     echo "Error: 'name' or 'ip' is missing from POST data.";
// }

// $db->close();
?>



