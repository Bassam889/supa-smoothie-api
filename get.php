<?php 
  require_once './initializers/config.php';
  // require 'vendor/autoload.php';

  // use Dotenv\Dotenv;  // Make sure this line is included

  // $dotenv = Dotenv::createImmutable(__DIR__);
  // $dotenv->load();

  // // $smth = $_ENV['DB_USER'];
  // // echo "DB_USER $smth";
  // // print_r($_ENV);

  use Main as Request;
  

  if (Request::check("GET")) {
    $smoothie = new Smoothie();
    if (isset($_GET['id'])) $smoothie->read(trim($_GET['id']));
    $smoothie->read();
  }
?>