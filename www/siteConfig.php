<?php
putenv("TZ=Australia/Sydney");
date_default_timezone_set("Australia/Sydney");
global $username;
global $password;
global $dbhost;
global $database;
global $db;
$db = null;

global $caches;
$caches = array(
  'tag' => __DIR__.'/data/tag.json',
  'model'=> __DIR__.'/data/model.json',
  'pack' => __DIR__.'/data/pack.json',
  'site' => __DIR__.'/data/site.json'
);

global $sites;
$sites = array(161);

require_once 'cacheUtil.php';
require_once 'dbUtil.php';
