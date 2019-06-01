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

//This is a website
require 'config.php';
$where="WHERE `sensorid`='1'";
if(isset($_GET['sensorid'])) {
  $where="WHERE `sensorid`='".$mysqli->real_escape_string($_GET['sensorid'])."'";
}

$q=$mysqli->query("SELECT `ts`,`temp`,`hum` FROM `data` $where ORDER BY `ts` DESC LIMIT 1");
if(!$q)
  die(mysqli_error($mysqli));
$r=mysqli_fetch_assoc($q);
if(isset($_GET['raw'])) {
  header("Content-type: text/plain");
  header("Cache-Control: no-cache");
  echo $r['temp'].' '.$r['hum'].' '.$r['ts'];
}
else if(isset($_GET['json'])) {
  header("Content-type: application/json");
  header("Cache-Control: no-cache");
  echo json_encode($r);
}
else{
  header("Cache-Control: no-cache");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>OpenDHT</title>
    <link href='style.css' rel='stylesheet'>
    <script type="text/javascript">
      function fetchData() {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() { 
          if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var data=JSON.parse(xmlHttp.responseText);
            document.getElementById('temp').innerHTML=data.temp+"&#176;C";
            document.getElementById('hum').innerHTML=data.hum+"%";
            document.getElementById('ts').innerHTML=data.ts;
          }
        }
        var sensorid=document.getElementById('sensorselect').value;
        xmlHttp.open("GET", location.href+"?json=1&sensorid="+sensorid, true);
        xmlHttp.send(null);
      }
      function test_javascript() {
        document.getElementById('jstest').innerHTML="Auto-refresh enabled<br>";
        setInterval(fetchData, 300000);
      }
    </script>
  </head>
  <body onLoad='test_javascript()'>
    <noscript>
      Warning: Javascript is used for auto-refresh feature<br/>
      However, you'r browser either doesn't support it, or<br/>
      it's disabled. Auto-refresh will not work, you have to<br/>
      refresh page manually.<br/>
    </noscript>
    <select id=sensorselect onChange='fetchData()'>
<?php


$qqq=$mysqli->query("SELECT `location`,`type`,`id` FROM `sensors`");
while($r2 = mysqli_fetch_row($qqq)) {
  $id=$r2[2];
  $type=$r2[1];
  $loc=$r2[0];
  echo "<option value='$id'>$loc - $type</option>";
}
?>
    </select><br/>
    <label for='temp' class='lbl'>Temperature</label><br/><span class='value' id=temp><?=$r['temp']; ?>&#176;C</span><br/>
    <label for='hum' class='lbl'>Humidity</label><br/><span class='value' id=hum><?=$r['hum']; ?>%</span><br/>
    <br/>
    <a class='ninjalink' href='plots-en.php'>Plots</a><br/>
    <span class='ninja'>
      Timestamp: <span id=ts><?=$r['ts']; ?></span><br/>
    </span>
    <span id=jstest class=ninja></span>
    <span class='ninja smalltext'>
      <a href='https://github.com/LukaszMoskala/OpenDHT' class='ninjalink'>OpenDHT</a> &copy; 2019 Łukasz Konrad Moskała &lt;<a class='ninjalink' href='mailto:lm@lukaszmoskala.pl'>lm@lukaszmoskala.pl</a>&gt;<br/>
      <a href='https://plot.ly' class='ninjalink'>Plot.ly</a> is used to generate plots<br/>
    </span>
  </body>
</html>

<?php
}
