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

$whereval="AND date(`ts`)=CURRENT_DATE()";


require 'config.php';
if(isset($_GET['date'])) {
  $whereval = "AND date(`ts`)='".$mysqli->real_escape_string($_GET['date'])."'";
}
if(isset($_GET['range'])) {
  $a=$_GET['range'];
  if($a == "2") {
    $whereval = "AND `ts` >= DATE_SUB(NOW(),INTERVAL 1 HOUR)";
  }
}
$sid=1;
if(isset($_GET['sensorid'])) {
  $sid=$mysqli->real_escape_string($_GET['sensorid']);
}

$q=$mysqli->query("SELECT `ts`,`temp`,`hum` FROM `data` WHERE `sensorid`='$sid' $whereval ORDER BY `ts` ASC");
if(!$q)
  die(mysqli_error($mysqli));

$x_values_temp=array();
$x_values_hum=array();
$y_values=array();

while($r = mysqli_fetch_row($q)) {
  array_push($y_values, $r[0]);
  array_push($x_values_temp, $r[1]);
  array_push($x_values_hum, $r[2]);
}
header("content-type: application/json");
echo json_encode( Array("temp" => $x_values_temp, "hum" => $x_values_hum, "ts" => $y_values));
