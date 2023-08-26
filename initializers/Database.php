<?php
  require_once __DIR__ . '/../vendor/autoload.php';

  use Dotenv\Dotenv as Dotenv;
  
  class Database 
  {

    public $db_host;
    public $db_name;
    public $db_username;
    public $db_password;

    function __construct()
    { 
      try {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
  
        $this->db_host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->db_username = $_ENV['DB_USER'];
        $this->db_password = $_ENV['DB_PASS'];

        $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8";
        $db_connection = new PDO($dsn, $this->db_username, $this->db_password);
        $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db_connection;
      } catch (PDOException $e) {
        echo "Connection error {$e->getMessage()}";
        exit;
      }
    }
  }
?>