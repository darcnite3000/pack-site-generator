<?php

function load_cache ($file, $default = "{}") {
  $data_string = $default;
  if(file_exists($file) && filesize($file) > 0){
    $h = fopen($file, 'r');
    $data_string = fread($h,filesize($file));
    fclose($h);
  }else{
    save_cache($file, $data_string, false);
  }
  return json_decode($data_string, true);
}

function save_cache ($file, $data, $encode = true) {
  $h = fopen($file, 'w');
  $data_string = $encode ? json_encode($data) : $data;
  fwrite($h, $data_string);
  fclose($h);
}

function data_filter ($data, $filter = array()) {
  $out = array();
  $type = isset($filter['type']) ? $filter['type'] : 'byKey';
  if ($type == 'byKey'){
    $key = isset($filter['key']) ? $filter['key'] : 'id';
    $values = isset($filter['values']) ? $filter['values'] : array();
    $out = data_filter_by_key($data, $key, $values);
  }
  return array_values($out);
}

function data_filter_by_key ($data, $key, $values) {
  return array_filter($data, function($item) use ($key, $values){
    if(is_array($item[$key])){
      return count(array_intersect($item[$key],$values)) > 0;
    }
    return in_array($item[$key], $values);
  });
}

function get_id ($data, $id) {
  return data_filter_by_key($data, 'id', array($id));
}
