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

require 'config.php';


$where="WHERE `sensorid`='1'";
if(isset($_GET['sensorid'])) {
  $where="WHERE `sensorid`='".$mysqli->real_escape_string($_GET['sensorid'])."'";
}

$q=$mysqli->query("SELECT `ts`,`temp`,`hum` FROM `data` $where ORDER BY `ts` DESC LIMIT 1");
if(!$q)
  die(mysqli_error($mysqli));
$r=mysqli_fetch_assoc($q);

if(isset($_GET['json'])) {
  header("Content-type: application/json");
  header("Cache-Control: no-cache");
  echo json_encode($r);
}
else {
  header("Content-type: text/plain");
  header("Cache-Control: no-cache");
  echo $r['temp'].' '.$r['hum'].' '.$r['ts'];
}