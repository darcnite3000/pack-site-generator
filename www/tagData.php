<?php

function load_tags_data (){
  global $caches;
  $data = load_tags_data_cache($caches['tag']);
  return $data['tags'];
}

function load_tags_data_cache ($dataFile) {
  return load_cache($dataFile, '{"tags":{}}');
}

function update_tags_data ($data, $tags, $site) {
  foreach ($tags as $tag) {
    if (!isset($data['tags'][$tag])) {
      $data['tags'][$tag] = array('id' => $tag, 'onSites' => array($site));
    }else{
      if (!in_array($site, $data['tags'][$tag]['onSites'])) {
        $data['tags'][$tag]['onSites'][] = $site;
      }
    }
  }
  return $data;
}
