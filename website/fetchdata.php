<?php
/*
  Copyright (C) 2019 Łukasz Konrad Moskała
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
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
