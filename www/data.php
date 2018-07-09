<?php
require_once 'siteConfig.php';

$type = strtolower($_REQUEST['type']);
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

switch ($type) {
  case 'models':
    display_models($id);
    break;
  case 'tags':
    display_tags($id);
    break;
  case 'sites':
    display_sites($id);
    break;
  case 'packs':
    display_packs($id);
    break;
  default:
    display_404($type, $id);
}

function display_models ($id) {
  global $sites;
  require_once 'modelData.php';
  $data = data_filter(load_models_data(), array('key'=>'onSites', 'values'=>$sites));
  if ($id) display_id('models', $id);
  display_output('models', $data);
}

function display_tags ($id) {
  global $sites;
  require_once 'tagData.php';
  $data = data_filter(
    load_tags_data(),
    array('key'=>'onSites', 'values'=>$sites));
  if ($id) display_id('tags', $id);
  display_output('tags', $data);
}

function display_sites ($id) {
  global $sites;
  require_once 'siteData.php';
  $data = data_filter(
    load_sites_data($sites),
    array('values'=>$sites));
  if ($id) display_id('sites', $id);
  display_output('sites', $data);
}
function display_packs ($id) {
  global $sites;
  require_once 'packData.php';
  $data = data_filter(
    load_packs_data($sites),
    array('key'=>'siteId', 'values'=>$sites));
  if ($id) display_id('packs', $id, $data);
  display_output('packs', $data);
}

function display_404 ($type, $id) {
  json_output(array('error'=>"Could not find requested content", "type"=>$type, "id"=>$id));
}

function display_id ($type, $id, $data = array()) {
  $data = get_id($data, $id);
  if (count($data) == 1) {
    display_output($type, $data);
  } else {
    display_404('packs',$id);
  }
}
function display_output ($type, $data = array()) {
  $output = array($type => $data);
  if ($type == 'packs'){
    $output['models'] = load_models_for_packs($data);
  }
  json_output($output);
}

function json_output($data, $status = 200){
  header('Content-type:application/json;charset=utf-8');
  switch ($status) {
    case 404:
      header("HTTP/1.1 404 Not found");break;
    case 206:
      header("HTTP/1.1 206 Partial content");break;
    default:
      header("HTTP/1.1 200 OK");
  }
  echo json_encode($data);
  exit;
}

