<?php

function load_models_data () {
  global $caches;
  $data = load_models_data_cache($caches['model']);
  return $data['models'];
}

function load_models_data_cache ($dataFile) {
  return load_cache($dataFile, '{"models":{}}');
}

function update_models_data ($data, $models) {
  foreach ($models as $model) {
    if(!isset($data['models'][$model['id']])){
      $data['models'][$model['id']] = $model;
    }else{
      $sites = array_unique(array_merge($data['models'][$model['id']]['onSites'], $model['onSites']));
      $data['models'][$model['id']]['onSites'] = $sites;
    }
  }
  return $data;
}
