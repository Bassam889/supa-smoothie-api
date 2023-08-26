<?php 
  class Main {
    static function check($req)
    {
      if ($_SERVER["REQUEST_METHOD"] === $req) {
        return true;
      }
      static::json(0, 405, "Invalid Request Method. HTTP method should be {$req}");
    }

    static function json(int $ok, $status, $msg, $key = false, $value = false)
    {
      $res = ["ok" => $ok];
      if ($status !== null) {
        http_response_code($status);
        $res["status"] = $status;
      }
      if ($msg !== null) $res["message"] = $msg;

      if ($value) {
        if ($key) {
          $res[$key] = $value;
        } else {
          $res["data"] = $value;
        }
      }
      echo json_encode($res);
      exit;
    }

    static function _404()
    {
      static::json(0, 404, "Not Found!");
    }
  }
?>