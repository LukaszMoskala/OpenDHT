-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas generowania: 04 Cze 2019, 13:28
-- Wersja serwera: 5.7.26-0ubuntu0.16.04.1
-- Wersja PHP: 7.0.33-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `dht`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `data`
--

CREATE TABLE `data` (
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `temp` double NOT NULL,
  `hum` double NOT NULL,
  `sensorid` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sensors`
--

CREATE TABLE `sensors` (
  `id` int(11) NOT NULL,
  `addr` varchar(64) NOT NULL,
  `location` text NOT NULL,
  `type` enum('esp8266-http','local-tmp','local-shm','esp8266-tcp','fakesensor') NOT NULL DEFAULT 'esp8266-http',
  `reading_enabled` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD KEY `sensorid` (`sensorid`);

--
-- Indexes for table `sensors`
--
ALTER TABLE `sensors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `sensors`
--
ALTER TABLE `sensors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `data`
--
ALTER TABLE `data`
  ADD CONSTRAINT `data_ibfk_1` FOREIGN KEY (`sensorid`) REFERENCES `sensors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
