<?php
//Alternative to fetch.php
//esp8266 connects and sends data to this script
//instead of script connecting to ESP

//THIS IS UNTESTED!!!!!!!!!!!!!!!!!!!!!!1
//if it works (or not) report to lm@lukaszmoskala.pl
//or open issue on github project page

require 'config.php';

if($_SERVER['REMOTE_ADDR'] != $DHT_IP) {
  header("HTTP/1.1 401 Unauthorized");
  die("This IP is NOT authorized. Edit your config.php");
}

if(!isset($_GET['temp']) || !isset($_GET['hum'])) {
  //no data received, exit gracefully
  die();
}
$h = $mysqli->real_escape_string($_GET['temp']);
$t = $mysqli->real_escape_string($_GET['hum']);

$q=$mysqli->query("INSERT INTO `data`(`temp`,`hum`) VALUES('$t','$h')");
if(!$q)
  die(mysqli_error($mysqli));
