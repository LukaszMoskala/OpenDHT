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

$qqq=$mysqli->query("SELECT `addr`,`type`,`id` FROM `sensors` WHERE `reading_enabled`='1'");
while($r = mysqli_fetch_row($qqq)) {
  $addr=$r[0];
  $type=$r[1];
  $data="";
  if($type == 'esp8266-http') {
    $data=file_get_contents("http://$addr/");
  }
  else if($type == 'local-tmp') {
    $data=file_get_contents("/tmp/dht");
  }
  else if($type == 'local-shm') {
    $data=file_get_contents("/dev/shm/dht");
  }
  else if($type == "esp8266-tcp") {
    $data="";
    echo "$type is not implemented yet.\n";
  }
  if(strlen($data) > 0) {
    $x=explode(" ",$data);
    if(!isset($x[0])) {
      continue;
    }
    $h = $mysqli->real_escape_string($x[0]);
    $t = $mysqli->real_escape_string($x[1]);
    $i = $mysqli->real_escape_string($r[2]); //yes, $r is correct here
    $q=$mysqli->query("INSERT INTO `data`(`temp`,`hum`,`sensorid`) VALUES('$t','$h','$i')");
    if(!$q) {
      echo mysqli_error($mysqli)."\n";
      continue;
    }
  }
}


