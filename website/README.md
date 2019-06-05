# Setup database
```
mysql -u root -p < mysql-user-db.sql
mysql -u root -p < mysql-structure.sql
```
# Setup website
Copy `*.{php,js,css}` and `lang` to your web server directory. I'm not a web developer so
this may not look pretty, but at least it have a dark theme.
# Setup script to receive data
Use `crontab -e` to edit crontab. You should do this as user
that is used by your web server. On ubuntu server, this is `www-data`.
To read data every 5 minutes, use
```
0,5,10,15,20,25,30,35,40,50,55 * * * * /usr/bin/php -f /var/www/fetchdata.php
```
In `config.php` you can set default language
Setup your sensors in table `sensors` in database!

`addr` is IP address of your sensor

`location` is human-friendly location of your sensor, like `outside` or `basement`

`type` is type of sensor. Currently only `esp8266-http` is supported

`reading_enabled` tells script whether it should try to read data or not.
Use this field to disable sensors for maintenance/firmware upgrade
