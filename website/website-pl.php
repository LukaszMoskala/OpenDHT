<?php
//This is a website
$mysqli=mysqli_connect("localhost", "dht","","dht");
if(!$mysqli)
  die(mysqli_error($mysqli));

$q=$mysqli->query("SELECT `ts`,`temp`,`hum` FROM `data` ORDER BY `ts` DESC LIMIT 1");
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
        xmlHttp.open("GET", location.href+"?json=1", true);
        xmlHttp.send(null);
      }
      function test_javascript() {
        document.getElementById('jstest').innerHTML="JavaScript aktywny, dane będą odświeżane automatycznie<br>";
        setInterval(fetchData, 300000);
      }
    </script>
  </head>
  <body onLoad='test_javascript()'>
    <noscript>
      Uwaga: JavaScript jest używany do automatycznego odświeżania danych.
      Jednakże, Twoja przeglądarka go nie wspiera, lub jest wyłączony.<br/>
      Oznacza to że dane nie będą odświeżane automatycznie.<br/>
    </noscript>
    <label for='temp' class='lbl'>Temperatura</label><br/><span class='value' id=temp><?=$r['temp']; ?>&#176;C</span><br/>
    <label for='hum' class='lbl'>Wilgotność</label><br/><span class='value' id=hum><?=$r['hum']; ?>%</span><br/>
    <br/>
    <a class='ninjalink' href='plots-pl.php'>Wykresy</a><br/>
    <span class='ninja'>
      Czas odczytu danych: <span id=ts><?=$r['ts']; ?></span><br/>
    </span>
    <span id=jstest class=ninja></span>
    <span class='ninja smalltext'>
      <a href='https://github.com/LukaszMoskala/OpenDHT' class='ninjalink'>OpenDHT</a> &copy; 2019 Łukasz Konrad Moskała &lt;<a class='ninjalink' href='mailto:lm@lukaszmoskala.pl'>lm@lukaszmoskala.pl</a>&gt;<br/>
      <a href='https://plot.ly' class='ninjalink'>Plot.ly</a> jest używane do generowania wykresów<br/>
    </span>
  </body>
</html>

<?php
}
