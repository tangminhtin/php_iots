<?php
$file = 'jsonFile.json';  // name of json file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  // get update data with json format from client and save json format to jsonFile.json when method POST is occurred
  file_put_contents($file, $_POST["json"]);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') { // when method GET is occurred, send json data to client
  echo file_get_contents($file);
}
?>