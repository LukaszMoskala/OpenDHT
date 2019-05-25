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
?>
<!DOCTYPE html>
<html>
  <head>
    <title>OpenDHT - Plots</title>
    <link href='style.css' rel='stylesheet'>
    <script src="plotly.min.js"></script>
    <script type="text/javascript">
      function plotPlot() {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() { 
          if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var data=JSON.parse(xmlHttp.responseText);

            var layout1 = {
              title: 'Temperature',
            };
            var layout2 = {
              title: 'Humidity',
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
            Plotly.newPlot('plot1',d1, layout1);
            Plotly.newPlot('plot2',d2, layout2);
          }
        }
        xmlHttp.open("GET", "fetch-plot-data.php", true);
        xmlHttp.send(null);
      }
    </script>
  </head>
  <body onLoad='plotPlot()'>
    <div id='plot1'></div>
    <div id='plot2'></div>
    <span class='ninja smalltext'>
      <a href='https://github.com/LukaszMoskala/OpenDHT' class='ninjalink'>OpenDHT</a> &copy; 2019 Łukasz Konrad Moskała &lt;<a class='ninjalink' href='mailto:lm@lukaszmoskala.pl'>lm@lukaszmoskala.pl</a>&gt;<br/>
      <a href='https://plot.ly' class='ninjalink'>Plot.ly</a> is used to generate plots<br/>
    </span>
  </body>
</html>
