<?php 
  if (!isset($allow_method)) $allow_method = "GET, POST, PUT, DELETE, OPTIONS";
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: $allow_method");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-Width");

  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
  }
  require_once "./handlers/main.php";
  require_once "./models/smoothie.php";
?>