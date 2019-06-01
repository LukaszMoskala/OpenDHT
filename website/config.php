<?php

//you should create database `dht`, and create table as in `database.sql`.
//Then, you should create user `dht` and give it limited access to database `dht`
//that is, only SELECT and INSERT access

//database information
$DB_IP = "localhost";
$DB_USER = "dht";
$DB_PASS = "";
$DB_DATABASE = "dht";

$mysqli=mysqli_connect("localhost", "dht","","dht");
if(!$mysqli)
  die(mysqli_error($mysqli));