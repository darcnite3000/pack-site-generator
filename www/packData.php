<?php
require_once 'tagData.php';
require_once 'modelData.php';

function load_packs_data ($sites = array()) {
  global $caches;
  $lastWeek = time() - (7 * 24 * 60 * 60);
  $packs_data = load_packs_data_cache($caches['pack']);
  $tags_data = load_tags_data_cache($caches['tag']);
  $models_data = load_models_data_cache($caches['model']);
  if ($packs_data['lastUpdate'] < $lastWeek) {
    $db = lazy_db();

    $query = "SELECT * FROM tblPacks ";
    $query.= "WHERE updated_at > {$packs_data['lastUpdate']} ";
    $where = implode(' OR ',array_map('sql_wrap_paysite_ids', $sites));
    if ($where!='') $query.= "AND ($where) ";
    $result = $db->query($query);

    $query = "SELECT DISTINCT tblPacks.PackID, tblPacks.PaysiteID, tblModels.ModelID, tblModels.ModelName ";
    $query.= "FROM tblPacks ";
    $query.= "JOIN brgPackModel ON tblPacks.PackID = brgPackModel.PackID ";
    $query.= "JOIN tblModels ON brgPackModel.ModelID = tblModels.ModelID ";
    $query.= "WHERE updated_at > {$packs_data['lastUpdate']} ";
    if ($where!='') $query.= "AND ($where) ";
    $modelResult = $db->query($query);
    $modelMap = array();
    $models = array();
    while($row = $modelResult->fetch_assoc()){
      $pid = $row['PackID'];
      $sid = $row['PaysiteID'];
      $mid = $row['ModelID'];
      $name = $row['ModelName'];
      $modelMap[$pid][] = $mid;
      if (!isset($models[$mid])) {
        $models[$mid] = array('id'=>$mid, 'name'=>$name, 'onSites'=>array($sid));
      } else {
        if (!in_array($sid, $models[$mid]['onSites'])) {
          $models[$mid]['onSites'][] = $sid;
        }
      }
    }
    $models_data = update_models_data($models_data, $models);

    while ($row = $result->fetch_assoc()) {
      $pack = array(
        'id' => $row['PackID'],
        'siteId' => $row['PaysiteID'],
        'date' => strtotime($row['PackDate']),
        'title' => $row['Title'],
        'desc' => $row['Desc'],
        'tags' => explode_marketing($row['Marketing']),
        'models' => (isset($modelMap[$row['PackID']])) ? $modelMap[$row['PackID']] : array(),
        'hasVideo' => ($row['MPA_VA']!== null && $row['MPA_VA']!=''),
        'hasPhoto' => ($row['MPA_PIC']!== null && $row['MPA_PIC']!='')
      );
      $packs_data['packs'][$row['PackID']] = $pack;
      $tags_data = update_tags_data($tags_data, $pack['tags'], $pack['siteId']);
    }
    $packs_data['lastUpdate'] = time();
    save_cache($caches['tag'], $tags_data);
    save_cache($caches['model'], $models_data);
    save_cache($caches['pack'], $packs_data);
  }
  return $packs_data['packs'];
}

function load_packs_data_cache ($dataFile) {
  return load_cache($dataFile, '{"lastUpdate":0,"packs":{}}');
}

function load_models_for_packs ($packs) {
  return data_filter(
    load_models_data(),
    array('values'=>collect_packs_models($packs)));
}

function collect_packs_models($packs){
  return array_reduce($packs, function($models, $pack){
    return array_unique(array_merge($models, $pack['models']));
  }, array());
}

function explode_marketing ($marketing) {
  return array_filter(
    array_unique(
      array_map(
        function ($tag) {
          return strtolower(trim($tag));
        },
        explode(',',$marketing))),
    function ($tag) { return $tag !== ''; });
}
