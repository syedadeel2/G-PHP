<?php namespace API\Application;

require_once '../../utils.php';

use Helpers\Utils;

header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, x-api-key");

if (Utils::validateHeaders(false)) {

    var_dump($_SERVER);
}
