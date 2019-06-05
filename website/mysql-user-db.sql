#Create user
# - without password
# - able to login only from local machine
CREATE USER 'dht'@'localhost';

#Create database to store data from sensor
CREATE DATABASE `dht`;

#Grant limited access to previously created user
#to created database
#we need only select and insert access

#you should NOT give user more permissions than neccesary
GRANT SELECT, INSERT ON `dht`.* TO 'dht'@'localhost';
