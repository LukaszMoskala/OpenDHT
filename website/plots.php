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

?>
<!DOCTYPE html>
<html>
  <head>
    <title><?=$LANG_TITLE; ?> - <?=$LANG_PLOTS;?></title>
    <link href='style.css' rel='stylesheet'>
    <link href='DatePickerX.min.css' rel='stylesheet'>
    <script src="plotly.min.js"></script>
    <script src="DatePickerX.min.js"></script>
    <script type="text/javascript">
      function plotPlot() {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() { 
          if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var data=JSON.parse(xmlHttp.responseText);

            var d1 = 
              {
                y: data.temp,
                x: data.ts,
                type: 'scatter',
                name: '<?=$LANG_TEMPERATURE;?>',
                hoverinfo: 'all',
                line: {shape: 'spline'}
              };
            var d2 = 
              {
                y: data.hum,
                x: data.ts,
                type: 'scatter',
                name: '<?=$LANG_HUMIDITY;?>',
                yaxis: 'y2',
                hoverinfo: 'all',
                line: {shape: 'spline'}
              }
            ;
            var ddd = [d1, d2];
            var layout = {
              showlegend: false,
              title: 'OpenDHT',
              titlefont: {color: '#fff'},
              plot_bgcolor: '#333333',
              paper_bgcolor: '#222222',
              yaxis: {
                title: '<?=$LANG_TEMPERATURE;?>',
                titlefont: {color: '#fff'},
                tickfont: {color: '#fff'},
                side: 'left',
                fixedrange: true
              },
              yaxis2: {
                title: '<?=$LANG_HUMIDITY;?>',
                titlefont: {color: '#fff'},
                tickfont: {color: '#fff'},
                overlaying: 'y',
                side: 'right',
                position: 1,
                fixedrange: true
              },
              xaxis: {
                color: '#fff',
                fixedrange: true
              },
              clickmode: false
            };
            Plotly.newPlot('plot1',ddd, layout,{displayModeBar: false, locale: 'pl'});
          }
        }
        var dh=document.getElementById('date_input');
        var sensorid=document.getElementById('sensorselect').value;
        if(dh.value.length > 0) {
          xmlHttp.open("GET", "fetch-plot-data.php?date="+dh.value+"&sensorid="+sensorid, true);
        }
        else {
          xmlHttp.open("GET", "fetch-plot-data.php?sensorid="+sensorid, true);
        }
        xmlHttp.send(null);
      }
      window.addEventListener('DOMContentLoaded', function()
        {
          var myDatepicker = document.getElementById('date_input');
          myDatepicker.DatePickerX.init({
            format: "yyyy-mm-dd"
          });
        });
    </script>
  </head>
  <body onLoad='plotPlot()'>
    <div id='plot1'></div>
    <input type='text' id='date_input' onChange='plotPlot()' placeholder='<?=$LANG_DATE_FORMAT;?>'>
    <select id=sensorselect onChange='plotPlot()'>
<?php
$qqq=$mysqli->query("SELECT `location`,`type`,`id` FROM `sensors`");
while($r2 = mysqli_fetch_row($qqq)) {
  $id=$r2[2];
  $type=$r2[1];
  $loc=$r2[0];
  echo "<option value='$id'>$loc - $type</option>";
}
?>
    </select>
    <button onClick='plotPlot()'><?=$LANG_REFRESH;?></button><br/>
    <span class='ninja smalltext'>
      <a href='https://github.com/LukaszMoskala/OpenDHT' class='ninjalink'>OpenDHT</a> &copy; 2019 Łukasz Konrad Moskała &lt;<a class='ninjalink' href='mailto:lm@lukaszmoskala.pl'>lm@lukaszmoskala.pl</a>&gt;<br/>
      <?=$LANG_PLOTLY_CREDIT;?><br/>
    </span>
  </body>
</html>
