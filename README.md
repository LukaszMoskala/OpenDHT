# Project goal
This project aims to create simple website with current temperature and humidity
from sensor connected to `ESP8266`. Support for Raspberry Pi is not ready yet.
# Architecture
ESP8266 acts as web server, and provides temperature and humidity in following format:
```
xx.xx yy.yy
```
Where xx.xx is humidity, and yy.yy is temperature.

On more powerfull server (I use VPS for that, you can use Raspberry pi or really anything that can run `CRON`, web server, `MySQL` and `PHP`), there is cron script that runs every 5 minutes,
which connects to sensor, receives data, and saves it to MySQL database.

On the same server, you can setup website that shows current temperature
and humidity. You can see [live example here](https://lukaszmoskala.pl/OpenDHT/website.php?lang=en).
# Plots
Plots are generated in browser, using plot.ly. See example [here](https://lukaszmoskala.pl/OpenDHT/plots.php?lang=en)
# Scripting API
website provides API for [raw data](https://lukaszmoskala.pl/OpenDHT/fetch-latest-data.php) and [JSON data](https://lukaszmoskala.pl/OpenDHT/fetch-latest-data.php?json=1)

# Todo
```
Integration with OpenWeatherMap
Support for more platforms
Support for more sensors
```
