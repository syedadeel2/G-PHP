<?php namespace API\Application;

require_once '../../data_context/db.php';
require_once '../../data_context/models/application.model.php';
require_once '../../data_context/repos/application.php';

use Data\DataContext;
use Models\Application;
use Repositories\ApplicationRepository;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get posted data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->name) && !empty($data->description)) {

 $db        = new \Data\DataContext();
 $dbContext = $db->createContext();

 $app              = new Application();
 $app->name        = $data->name;
 $app->description = $data->description;

 $app_repo = new ApplicationRepository($dbContext);
 if ($app_repo->insert($app)) {

  // set response code - 201 created
  http_response_code(201);

  // tell the user
  echo json_encode(array("message" => "Application was created."));

 } else {

  // set response code - 503 service unavailable
  http_response_code(503);

  // tell the user
  echo json_encode(array("message" => "Unable to create application."));

 }

} else {
 // set response code - 400 bad request
 http_response_code(400);

 // tell the user
 echo json_encode(array("message" => "Unable to create application. Data is incomplete."));
}
