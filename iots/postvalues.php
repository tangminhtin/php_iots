<?php
//Creates new record as per request
    //Connect to database
    

    // Create connection
    $conn = mysqli_connect("localhost","root","","IoTs");
    mysqli_set_charset($conn,"utf8");
    // Check connection
    if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }

    //Get current date and time
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $d = date("Y-m-d");
    //echo " Date:".$d."<BR>";
    $t = date("H:i:s");

    if(!empty($_POST['Temperature']))
    {
        $Humidity = $_POST['Humidity'];
    	$Temperature = $_POST['Temperature'];
    	$pH = $_POST['pH'];

	    $sql = "INSERT INTO giamsat (Humidity, Temperature, pH, d, t)
		
		VALUES ('".$Humidity."', '".$Temperature."', '".$pH."', '".$d."', '".$t."')";

		if ($conn->query($sql) === TRUE) {
		    echo "OK";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}


	$conn->close();
?>