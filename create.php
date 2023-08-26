<?php 
  $allow_method = "POST";

  require_once './initializers/config.php';

  use Main as Request;
  use Main as Response; 

  if (Request::check("POST")) {
    $data = json_decode(file_get_contents("php://input"));
    if (
      !isset($data->title) ||
      !isset($data->method) ||
      !isset($data->rating)
    ):
      $fields = [
        "title" => "Smoothie title",
        "method" => "Smoothie method",
        "rating" => "Smoothie rating",
      ];
      Response::json(0, 400, "Please fill all the required fields", "fields", $fields);
    elseif (
      empty(trim($data->title)) ||
      empty(trim($data->method)) ||
      empty(trim($data->rating))
    ):
      $fields = [];
      foreach($data as $key => $val) {
        if (empty(trim($val))) array_push($fields, $key);
      }
      Response::json(0, 400, "Oops! empty field detected 🤷.", "empty_fields", $fields);
    else:
      $smoothie = new Smoothie();
      $smoothie->create($data->title, $data->method, $data->rating);
    endif;
  }
?>