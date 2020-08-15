<?php namespace API\Application;

require_once '../../utils.php';

use Helpers\Utils;
use Models\Application;
use Repositories\ApplicationRepository;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, x-api-key");


/**
 * @param ApplicationRepository $app_repo
 */
function createApp(ApplicationRepository $app_repo)
{
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->name) && !empty($data->description) && !empty($data->app_api_slug)) {

        $app = new Application();
        $app->name = $data->name;
        $app->description = $data->description;
        $app->app_api_slug = $data->app_api_slug;
        $app->app_key = Utils::GUIDv4();

        if ($app_repo->insert($app)) {

            http_response_code(201);
            echo json_encode(array("message" => "Application was created.", "x-api-key" => $app->app_key));
            Utils::generateHtAccess();

        } else {

            http_response_code(503);
            echo json_encode(array("message" => "Unable to create application."));

        }

    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete payload. Please refer the application document."));
    }
}

/**
 * @param ApplicationRepository $app_repo
 */
function removeApp(ApplicationRepository $app_repo)
{
    if (!isset($_GET["id"])) {

        http_response_code(400);
        echo json_encode(array("message" => "application id is missing, make sure you have passed the id in url e.g. ?id="));

    } else {

        if ($app_repo->remove($_GET["id"])) {
            http_response_code(200);
            echo json_encode(array("message" => "Application Deleted"));
            Utils::generateHtAccess();
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete application."));
        }

    }
}

/**
 * @param ApplicationRepository $app_repo
 */
function getApps(ApplicationRepository $app_repo)
{
    if (!isset($_GET["id"])) {
        echo json_encode($app_repo->getAll());
    } else {
        $app = $app_repo->getById($_GET["id"]);
        if ($app == null) {
            http_response_code(400);
            echo json_encode(array("message" => "Given application id not found"));
        } else {
            echo json_encode($app[0]);
        }

    }
}


/**
 * @param ApplicationRepository $app_repo
 */
function updateApp(ApplicationRepository $app_repo)
{
    if (!isset($_GET["id"])) {

        http_response_code(400);
        echo json_encode(array("message" => "application id is missing, make sure you have passed the id in url e.g. ?id="));

    } else {
        $data = json_decode(file_get_contents("php://input"));
        if ($app_repo->update($data, $_GET["id"])) {
            http_response_code(200);
            echo json_encode(array("message" => "Application updated"));
            Utils::generateHtAccess();
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update application."));
        }

    }
}

if (Utils::validateHeaders(true)) {

    $app_repo = new ApplicationRepository();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        createApp($app_repo);

    } else if ($_SERVER["REQUEST_METHOD"] === "PUT") {

        updateApp($app_repo);

    } else if ($_SERVER["REQUEST_METHOD"] === "DELETE") {

        removeApp($app_repo);

    } else if ($_SERVER["REQUEST_METHOD"] === "GET") {

        getApps($app_repo);

    }

}
