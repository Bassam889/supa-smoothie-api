<?php 
  require_once "./initializers/Database.php";
  require_once "./handlers/Main.php";

  use Main as Response;

  class Smoothie extends Database
  {

    private $DB;

    public function __construct() {
      $this->DB = Database::__construct();
    }

    private function filter($data)
    {
      return htmlspecialchars(trim(htmlspecialchars_decode($data)), ENT_NOQUOTES);
    }

    public function create(string $title, string $method, int $rating)
    {
      $title = $this->filter($title);
      $method = $this->filter($method);
      $rating = $this->filter($rating);

      try {
        $sqlQuery = "INSERT INTO 
                    `smoothie`
                    (`title`, `method`, `rating`)
                    VALUES 
                    (:title, :method, :rating)";
                    
        $stmt = $this->DB->prepare($sqlQuery);
  
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":method", $method, PDO::PARAM_STR);
        $stmt->bindParam(":rating", $rating, PDO::PARAM_INT);
  
        $stmt->execute();
  
        $last_id = $this->DB->lastInsertId();
        Response::json(1, 201, "Smoothie has been created successfully", "smoothie_id", $last_id);        
      } catch (PDOException $e) {
        Response::json(0, 500, $e->getMessage());                
      }
    }

    public function read($id = false, $return = false)
    {
      try {
        $orderBy = $_GET['orderBy'] ?? 'created_at';
        $validColumns = ['created_at', 'title', 'rating'];

        if (!in_array($orderBy, $validColumns)) {
          $orderBy = 'created_at';
        }

        $sqlQuery = "SELECT * FROM `smoothie` ORDER BY `$orderBy` DESC";
        if ($id !== false) {
          if (is_numeric($id)) {
            $sqlQuery = "SELECT * FROM `smoothie` WHERE `id`='$id'";
          } else {
            Response::_404();
          }
        }
        $query = $this->DB->query($sqlQuery);
        if ($query->rowCount() > 0) {
          $allSmoothies = $query->fetchAll(PDO::FETCH_ASSOC);
          if ($id !== false) {
            if ($return) return $allSmoothies[0];
            Response::json(1, 200, null, "smoothie", $allSmoothies[0]);
          }
          Response::json(1, 200, null, "smoothie", $allSmoothies);          
        }
        if ($id !== false) {
          Response::_404();
        }
        Response::json(1, 200, "Pleaser Insert Some smoothies...", "smoothie", []);
      } catch (PDOException $e) {
        Response::json(0, 500, $e->getMessage());
      }
    }

    public function update(int $id, Object $data)
    {
      try {
        $sqlQuery = "SELECT * FROM `smoothie` WHERE `id`='$id'";
        $query = $this->DB->query($sqlQuery);
        if ($query->rowCount() > 0) {
          $the_smoothie = $query->fetch(PDO::FETCH_OBJ);

          $title = (isset($data->title) && !empty(trim($data->title))) ? $this->filter($data->title) : $the_smoothie->title;
          $method = (isset($data->method) && !empty(trim($data->method))) ? $this->filter($data->method) : $the_smoothie->method;
          $rating = (isset($data->rating) && !empty(trim($data->rating))) ? $this->filter($data->rating) : $the_smoothie->rating;

          $update_sqlQuery = "UPDATE `smoothie` SET `title`=:title, `method`=:method, `rating`=:rating, `updated_at`=NOW() WHERE `id`='$id'";
        
          $stmt = $this->DB->prepare($update_sqlQuery);

          $stmt->bindParam(":title", $title, PDO::PARAM_STR);
          $stmt->bindParam(":method", $method, PDO::PARAM_STR);
          $stmt->bindParam(":rating", $rating, PDO::PARAM_INT);

          $stmt->execute();

          Response::json(1, 200, "Smoothie Updated Successfully", "smoothie", $this->read($id, true));
        }

        Response::json(0, 404, "Invalid Smoothie ID.");
      } catch (PDOException $e) {
        Response::json(0, 500, $e->getMessage());
      }
    }

    public function delete(int $id)
    {
      try {
        $sqlQuery = "DELETE FROM `smoothie` WHERE `id`='$id'";
        $query = $this->DB->query($sqlQuery);
        if ($query->rowCount() > 0) {
          Response::json(1, 200, "Smoothie has been deleted successfully.");
        }        
        Response::json(0, 404, "Invalid Smoothie ID.");
      } catch (PDOException $e) {
        Response::json(0, 500, $e->getMessage());
      }
    }
  }
?>