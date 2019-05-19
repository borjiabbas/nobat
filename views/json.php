<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo  json_encode($data,JSON_UNESCAPED_UNICODE);
if(isset($ms)){
    $ms->close_db();
}