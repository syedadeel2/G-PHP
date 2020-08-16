<?php namespace API\Application;

require_once '../../utils.php';

use Helpers\Utils;
use Models\Application;
use Models\DomainWhitelist;
use Repositories\ApplicationRepository;
use Repositories\DomainWhitelistRepository;

header('Content-Type: application/json');

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

        $new_app_id = $app_repo->insert($app);
        if ($new_app_id != false) {

            insertOrModifyWhitelisting($data, $new_app_id);

            http_response_code(201);
            echo json_encode(array("message" => "Application was created.", "g-api-key" => $app->app_key));
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
 * @param $data
 * @param $new_app_id
 * @param bool $is_update
 * @return bool
 */
function insertOrModifyWhitelisting($data, $new_app_id, $is_update = false): bool
{
// check if there is cors property as well
    if (isset($data->cors) && is_array($data->cors)) {
        $whitelist_repo = new DomainWhitelistRepository();
        for ($i = 0; $i < sizeof($data->cors); $i++) {
            $cor = $data->cors[$i];

            if ($is_update == false) {
                $tmp_whitelist = new DomainWhitelist();

                $tmp_whitelist->application_id = $new_app_id;
                if (isset($cor->domain)) {
                    $tmp_whitelist->domain = $cor->domain;
                } else if (isset($cor->ip_address)) {
                    $tmp_whitelist->ip_address = $cor->ip_address;
                }

                $whitelist_repo->insert($tmp_whitelist);
            } else {
                $tmpObj = new \stdClass;

                if (isset($cor->domain) && isset($cor->updateWith)) {
                    $tmpObj->domain = $cor->updateWith;
                } else if (isset($cor->ip_address) && isset($cor->updateWith)) {
                    $tmpObj->ip_address = $cor->updateWith;
                }

                $whitelist_repo->update($tmpObj, $new_app_id, TRUE, isset($cor->domain) ? $cor->domain : null, isset($cor->ip_address) ? $cor->ip_address : null);
            }
        }
        return true;
    }

    return false;
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
    $app_whitelist_repo = new DomainWhitelistRepository();
    if (!isset($_GET["id"])) {
        $all = $app_repo->getAll();
        foreach ($all as $row) {
            $row->cors = $app_whitelist_repo->getByApplicationId($row->id);
        }
        echo json_encode($all);
    } else {
        $app = $app_repo->getById($_GET["id"]);
        if ($app == null) {
            http_response_code(400);
            echo json_encode(array("message" => "Given application id not found"));
        } else {
            $app_tmp = $app[0];
            $app_tmp->cors = $app_whitelist_repo->getByApplicationId($app_tmp->id);
            echo json_encode($app_tmp);
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

        $update_completed = false;
        if (insertOrModifyWhitelisting($data, $_GET["id"], TRUE)) {
            $update_completed = true;
        }

        if (!empty($data->name) || !empty($data->description) || !empty($data->app_api_slug)) {
            $tmpObj = new \stdClass;

            if (isset($data->name) && !empty($data->name)) $tmpObj->name = $data->name;
            if (isset($data->description) && !empty($data->description)) $tmpObj->description = $data->description;
            if (isset($data->app_api_slug) && !empty($data->app_api_slug)) $tmpObj->app_api_slug = $data->app_api_slug;

            if ($app_repo->update($tmpObj, $_GET["id"])) {
                $update_completed = false;
                http_response_code(200);
                echo json_encode(array("message" => "Application updated"));
                Utils::generateHtAccess();
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update application."));
            }
        }

        if ($update_completed == true) {
            http_response_code(200);
            echo json_encode(array("message" => "Application updated"));
            Utils::generateHtAccess();
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
