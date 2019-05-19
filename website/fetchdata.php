<?php
//You should run this in CRON script
//to run script every 5 minutes, you can use:

//0,5,10,15,20,25,30,35,40,50,55 * * * * /usr/bin/php -f /var/www/fetchdata.php
if(php_sapi_name() != "cli")
  die("I probably shouldn't be run this way");

require 'config.php';

$data=file_get_contents("http://$DHT_IP/");
$x=explode(" ",$data);
if(!isset($x[0])) {
  //no data received, exit gracefully
  die();
}
$h = $mysqli->real_escape_string($x[0]);
$t = $mysqli->real_escape_string($x[1]);

$q=$mysqli->query("INSERT INTO `data`(`temp`,`hum`) VALUES('$t','$h')");
if(!$q)
  die(mysqli_error($mysqli));
