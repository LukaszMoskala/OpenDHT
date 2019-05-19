# Setup database
```
mysql -u root -p < database.sql
```
# Setup website
Copy website-en.php to your web server directory. I'm not a web developer so
this may not look pretty, but at least it have a dark theme.
# Setup script to receive data
Use `crontab -e` to edit crontab. You should do this as user
that is used by your web server. On ubuntu server, this is `www-data`.
To read data every 5 minutes, use
```
0,5,10,15,20,25,30,35,40,50,55 * * * * /usr/bin/php -f /var/www/fetchdata.php
```
Remember to edit `config.php` and set correct IP