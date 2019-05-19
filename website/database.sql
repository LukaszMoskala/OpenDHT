
CREATE DATABASE IF NOT EXISTS `dht`;

USE `dht`;

CREATE TABLE IF NOT EXISTS `data` (
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `temp` double NOT NULL,
  `hum` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `data`
  ADD PRIMARY KEY (`ts`);

CREATE USER IF NOT EXISTS 'dht'@'localhost';

GRANT SELECT, INSERT ON `dht`.`data` TO 'dht'@'localhost'; 