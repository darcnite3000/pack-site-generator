<?php

function load_sites_data ($newSites = array()) {
  global $caches;
  $sites_data = load_sites_data_cache($caches['site']);
  $sites_to_load = [];
  $sc = count($newSites);
  for ($i = 0; $i < $sc; $i++) {
    $site = $newSites[$i];
    if (!isset($sites_data['sites'][$site])) {
      $sites_to_load[] = $site;
    }
  }
  if(count($sites_to_load) > 0){
    $db = lazy_db();
    $query = "SELECT PaysiteID, DomainID, PaysiteName, ShortName, Niche ";
    $query.= "FROM tblPaysites ";
    $query.= "WHERE ";
    $query.= implode(' OR ',array_map('sql_wrap_paysite_ids', $sites_to_load));
    if ($result = $db->query($query)) {
      while ($row = $result->fetch_assoc()) {
        // var_dump($row);
        $sites_data['sites'][$row['PaysiteID']] = array(
          'id' => $row['PaysiteID'],
          'domain' => $row['DomainID'],
          'name' => $row['PaysiteName'],
          'abbr' => $row['ShortName'],
          'isShemale'=> $row['Niche'] == 2
        );
      }
    }
    save_cache($caches['site'], $sites_data);
  }

  return $sites_data['sites'];
}

function load_sites_data_cache ($dataFile) {
  return load_cache($dataFile, '{"sites":{}}');
}

