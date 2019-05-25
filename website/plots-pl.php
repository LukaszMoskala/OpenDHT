<!DOCTYPE html>
<html>
  <head>
    <title>OpenDHT - Wykresy</title>
    <link href='style.css' rel='stylesheet'>
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
      <a href='https://plot.ly' class='ninjalink'>Plot.ly</a> jest używane do generowania wykresów<br/>
    </span>
  </body>
</html>
