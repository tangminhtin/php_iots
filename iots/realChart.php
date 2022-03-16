<?php
// setup config of database
$server_name = "localhost";
$username = "root";
$password = "";
$dbname = "IoTs";

// Create connection
$conn = new mysqli($server_name, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// query to select data in table
$sql = "SELECT id, Temperature, Humidity, pH, d, t FROM giamsat WHERE d=CURRENT_DATE";  // get all data of current date
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'GET') { // read json file
  // Create array
  $data = array();

  if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
      $data[] = $row; // store all data to array
    }
    echo json_encode($data);  // send json data to client
  } else {
    echo json_encode(""); // send empty json data to client when database don't have record
  }
}

$conn->close();
?>