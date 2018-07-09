<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$ext = pathinfo($path, PATHINFO_EXTENSION);
if (!empty($ext) && file_exists($_SERVER["DOCUMENT_ROOT"] . $path)) {
  return false;
} else {
  if(preg_match("/^\/data\/(?P<type>.*)\/(?P<id>.*)\/?/i", $path, $matches) ||
     preg_match("/^\/data\/(?P<type>.*)\/?/i", $path, $matches)){
    $_REQUEST['type'] = $matches['type'];
    if(isset($matches['id'])) $_REQUEST['id'] = $matches['id'];
    require "www/data.php";
  }else{
    require "www/index.html";
  }
}
