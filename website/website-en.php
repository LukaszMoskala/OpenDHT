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
    <style>
      * {
        font-family: 'Ubuntu mono', monospace;
        font-size: 120%;
      }
      body {
        background-color: #222222;
        color: white;
        text-align: center;
      }
      .value {
        color: cyan;
        font-size: 800%;
      }
      .lbl {
        color: yellow;
        font-size: 600%;
      }
      .ninja {
        color: #444444;
        text-decoration: none;
      }
      .ninjalink {
        text-decoration: none;
        color: #666666;
      }
      .smalltext {
        font-size: 80%;
      }
    </style>
    <script src="plotly.min.js"></script>
    <script type="text/javascript">
      function plotPlot() {

        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() { 
          if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var data=JSON.parse(xmlHttp.responseText);

            var layout1 = {
              title: 'Temperatura',
            };
            var layout2 = {
              title: 'Wilgotność',
            };
            
            var d1 = [
              {
                y: data.temp,
                x: data.ts,
                type: 'scatter'
              }
            ];
            var d2 = [
              {
                y: data.hum,
                x: data.ts,
                type: 'scatter'
              }
            ];
            Plotly.newPlot('plot2',d2, layout2);
            Plotly.newPlot('plot1',d1, layout1);
            
          }
        }
        xmlHttp.open("GET", "fetch-plot-data.php", true);
        xmlHttp.send(null);
      }
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
    <label for='temp' class='lbl'>Temperature</label><br/><span class='value' id=temp><?=$r['temp']; ?>&#176;C</span><br/>
    <label for='hum' class='lbl'>Humidity</label><br/><span class='value' id=hum><?=$r['hum']; ?>%</span><br/>
    <br/>
    <div id=plot1></div>
    <div id=plot2></div>
    <span class='ninja'>
      Timestamp: <span id=ts><?=$r['ts']; ?></span><br/>
    </span>
    <span class=ninja onclick='plotPlot()'>
      Click here to viev plot<br/>
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
