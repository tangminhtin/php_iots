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
$sql = "SELECT id, Temperature, Humidity, pH, d, t FROM giamsat ORDER BY id DESC LIMIT 1";  // get the last record in the database
$result = $conn->query($sql);

if ($result->num_rows > 0) {  // check database have record or not
  // Output data of each row
  while ($row = $result->fetch_assoc()) {
    extract($row);  // extract data from row
    // show the data of Temperature
    echo "<p>
    <i class='fa fa-thermometer-half' style='font-size:5.0rem;color:red;'></i> 
    <span class='dht-labels'>Temperature : </span> 
    <span id='TemperatureValue'>{$Temperature}</span>
    <sup class='units'>&deg;C</sup>
  </p>";

    // show the data of Humidity
    echo "<p>
    <i class='fa fa-tint' style='font-size:5.0rem;color:#75e095;'></i>
    <span class='dht-labels'>Humidity : </span>
    <span id='HumidityValue'>{$Humidity}</span>
    <sup class='units'>%</sup>
  </p>";

    // show the data of pH
    echo "<p>
    <i class='fa fa-tint' style='font-size:5.0rem;color:#62a1d3'></i> 
    <span class='dht-labels'>pH : </span>
    <span id='pHValue'>{$pH}</span>
    <sup class='units'>%</sup>
  </p>";

    // show the data of t and d
    echo "<p>
    <i class='far fa-clock' style='font-size:3.0rem;color:#e3a8c7;'></i>
    <span style='font-size:2.0rem;'>Time </span>
    <span id='time' style='font-size:2.0rem;'>{$t}</span>
  
    <i class='far fa-calendar-alt' style='font-size:3.0rem;color:#f7dc68';></i>
    <span style='font-size:2.0rem;'>Date </span>
    <span id='date' style='font-size:2.0rem;'>{$d}</span>
  </p>";
  }
} else {
  echo "No records found.";
}
$conn->close();
?>


<!-- Get data Temperature, Humidity and send it to client -->
<script type="text/javascript">
  var tem = "<?= $Temperature ?>";
</script>
<script type="text/javascript">
  var hu = "<?= $Humidity ?>";
</script>
<!-- <script type="text/javascript" src="script.js"></script> -->