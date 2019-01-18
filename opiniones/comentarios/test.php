<?php


$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");
echo "Connected to MySQL<br>";
?>