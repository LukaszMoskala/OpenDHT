<!DOCTYPE html>
<html>
  <head>
    <title>OpenDHT - Wykresy</title>
    <link href='style.css' rel='stylesheet'>
    <link href='DatePickerX.min.css' rel='stylesheet'>
    <script src="plotly.min.js"></script>
    <script src="plotly-locale-pl.js"></script>
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
                name: 'Temperatura',
                hoverinfo: 'all'
              };
            var d2 = 
              {
                y: data.hum,
                x: data.ts,
                type: 'scatter',
                name: 'Wilgotność',
                yaxis: 'y2',
                hoverinfo: 'all'
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
                title: 'Temperatura',
                titlefont: {color: '#fff'},
                tickfont: {color: '#fff'},
                side: 'left',
                fixedrange: true
              },
              yaxis2: {
                title: 'Wilgotność',
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
        if(dh.value.length > 0) {
          xmlHttp.open("GET", "fetch-plot-data.php?date="+dh.value, true);
        }
        else {
          xmlHttp.open("GET", "fetch-plot-data.php", true);
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
    <input type='text' id='date_input' placeholder='RRRR-MM-DD'>
    <button onClick='plotPlot()'>Odśwież</button><br/>
    <span class='ninja smalltext'>
      <a href='https://github.com/LukaszMoskala/OpenDHT' class='ninjalink'>OpenDHT</a> &copy; 2019 Łukasz Konrad Moskała &lt;<a class='ninjalink' href='mailto:lm@lukaszmoskala.pl'>lm@lukaszmoskala.pl</a>&gt;<br/>
      <a href='https://plot.ly' class='ninjalink'>Plot.ly</a> jest używane do generowania wykresów<br/>
    </span>
  </body>
</html>
