<?php

//you should create database `dht`, and create table as in `database.sql`.
//Then, you should create user `dht` and give it limited access to database `dht`
//that is, only SELECT and INSERT access

//database information
$DB_IP = "localhost";
$DB_USER = "dht";
$DB_PASS = "";
$DB_DATABASE = "dht";

$DEFAULT_LANG = 'pl';

//60 000 ms is 60 seconds
//300 000 ms is 5 minutes
$AUTOREFRESH_DELAY = 60000;

$used_lang=$DEFAULT_LANG;

if(isset($_GET['lang'])) {
  $l = $_GET['lang'];
  //remove characters that could lead to execution of
  //unwanted files
  $l = str_replace("/","",$l);
  $l = str_replace("\\","",$l);
  $l = str_replace(".","",$l);
  if(file_exists("lang-$l.php")) {
    require "lang-$l.php";
    $used_lang=$l;
  }
}

if(!isset($LANG_TITLE)) {
  //lang not loaded, load default
  require "lang-$DEFAULT_LANG.php";
}
$mysqli=mysqli_connect("localhost", "dht","","dht");
if(!$mysqli)
  die(mysqli_error($mysqli));