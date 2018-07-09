<?php
function lazy_db () {
  global $username;
  global $password;
  global $dbhost;
  global $database;
  global $db;
  if ($db !== null) return $db;
  $db = mysqli_connect($dbhost, $username, $password, $database);
  if (!$db) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    exit;
  }
  return $db;
}

function sql_wrap_paysite_ids ($id) {
  return "PaysiteID = '$id'";
}
