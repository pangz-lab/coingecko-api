<?php
$api = json_decode(file_get_contents("./coingecko-api.v3.json"), true);
$fileParameterList = [];
foreach($api["paths"] as $currentPath) {
  if(!isset($currentPath["get"]["parameters"]) || empty($currentPath["get"]["parameters"])) { continue; }
  
  foreach($currentPath["get"]["parameters"] as $key => $param) {
    $fileParameterList[$param["name"]] = $param;
  }
}

print_r($fileParameterList);
print_r(count($fileParameterList));
print_r(array_keys($fileParameterList));