<?php

namespace API\Generic;

require_once '../../utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/repos/generic.php';

use Helpers\Utils;
use Repositories\ApplicationRepository;
use Repositories\GenericRepository;

header('Content-Type: application/json');

function insertStoreData()
{

    $app_repo = new ApplicationRepository();
    $app = $app_repo->getByKey(getallheaders()["g-api-key"]);
    $generic_repo = new GenericRepository();

    $data = json_decode(file_get_contents("php://input"));
    $uris = processURI();
    $store_name = "$app->app_api_slug" . "_$uris[0]";

    // If len is 1 then mean user want to insert the data
    if (sizeof($uris) == 1) {

        $last_id = $generic_repo->insert($data, $store_name);

        if ($last_id != false) {
            http_response_code(201);
            echo json_encode(array("message" => "record inserted", "record_id" => $last_id, "status" => true));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to insert record.", "status" => false));
        }

    }

}

function removeStoreData()
{

    $app_repo = new ApplicationRepository();
    $app = $app_repo->getByKey(getallheaders()["g-api-key"]);
    $generic_repo = new GenericRepository();

    $uris = processURI();

    if (sizeof($uris) !== 2) {
        http_response_code(400);
        echo json_encode(array("message" => "Missing action parameter. Please refer to the document.", "status" => false));
        return;
    }

    $store_name = "$app->app_api_slug" . "_$uris[0]";

    // If len is 1 and value is all then remove all the values from table.
    if (sizeof($uris) == 2 && $uris[1] == "all") {

        if ($generic_repo->truncateTable($store_name)) {
            http_response_code(200);
            echo json_encode(array("message" => "store truncated", "status" => true));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to truncate store.", "status" => false));
        }

    } else if (sizeof($uris) == 2 && $uris[1] == "storage") {     // If len is 1 and value is storage then drop the table.

        if ($generic_repo->dropTable($store_name)) {
            http_response_code(200);
            echo json_encode(array("message" => "store deleted", "status" => true));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete store.", "status" => false));
        }

    } else if (sizeof($uris) == 2 && is_numeric($uris[1])) {

        if ($generic_repo->remove($uris[1], $store_name)) {
            http_response_code(200);
            echo json_encode(array("message" => "store record deleted", "status" => true));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete store record.", "status" => false));
        }

    }
}

function updateStoreData()
{
    $app_repo = new ApplicationRepository();
    $app = $app_repo->getByKey(getallheaders()["g-api-key"]);
    $generic_repo = new GenericRepository();

    $uris = processURI();

    if (sizeof($uris) !== 2) {
        http_response_code(400);
        echo json_encode(array("message" => "Missing action parameter. Please refer to the document.", "status" => false));
        return;
    }

    $store_name = "$app->app_api_slug" . "_$uris[0]";
    $data = json_decode(file_get_contents("php://input"));

    if (sizeof($uris) == 2 && is_numeric($uris[1])) {
        if ($generic_repo->update($data, $uris[1], $store_name)) {
            http_response_code(200);
            echo json_encode(array("message" => "store record updated", "status" => true));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update store record.", "status" => false));
        }
    }
}

function getOrFilterStoreData(bool $postFilter = false)
{

    $app_repo = new ApplicationRepository();
    $app = $app_repo->getByKey(getallheaders()["g-api-key"]);
    $generic_repo = new GenericRepository();

    $uris = processURI();

    if (sizeof($uris) < 2) {
        http_response_code(400);
        echo json_encode(array("message" => "Missing action parameter. Please refer to the document.", "status" => false));
        return;
    }

    $store_name = "$app->app_api_slug" . "_$uris[0]";

    if ($postFilter == false) {
        // GET ALL
        if (sizeof($uris) == 2 && $uris[1] == "all") {

            echo json_encode($generic_repo->getAll($store_name), JSON_NUMERIC_CHECK);

        } else if (sizeof($uris) == 2 && is_numeric($uris[1])) { // GET ONE

            echo json_encode($generic_repo->getById($uris[1], $store_name), JSON_NUMERIC_CHECK);

        }
    } else if($postFilter == true && sizeof($uris) == 2 && $uris[1] == "filter") {

        $data = json_decode(file_get_contents("php://input"));
        echo json_encode($generic_repo->getAll($store_name, $data), JSON_NUMERIC_CHECK);

    }


}

function processURI(): array
{
    $uris = explode('/', $_SERVER['REQUEST_URI']);
    $uris = array_splice($uris, 3);

    for ($i = 0; $i < sizeof($uris); $i++) {
        $pos = strpos($uris[$i], "?");
        if ($pos !== false) {
            if ($pos == 0) unset($uris[$i]);
            if ($pos > 0) $uris[$i] = substr($uris[$i], 0, strpos($uris[$i], "?"));
        }

    }


    return $uris;
}

if (Utils::validateHeaders(false)) {

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $uris = processURI();
        if (sizeof($uris) == 1) {

            insertStoreData();

        } elseif (sizeof($uris) == 2) {

            getOrFilterStoreData(TRUE);

        }

    } else if ($_SERVER["REQUEST_METHOD"] === "PUT") {

        updateStoreData();

    } else if ($_SERVER["REQUEST_METHOD"] === "DELETE") {

        removeStoreData();

    } else if ($_SERVER["REQUEST_METHOD"] === "GET") {

        getOrFilterStoreData();

    }

}

//insert data
//POST /api/app_name/store_name
//
//DELETE /api/app_name/store_name/9 << Delete One
//DELETE /api/app_name/store_name/all << Delete all
//DELETE /api/app_name/store_name/storage << Delete the store table


//PUT /api/app_name/store_name/9 << Update existing one


// Querys = ?cols=&order_by=&desc=true&start=&length=
//GET /api/app_name/store_name/all << Get All Records
//GET /api/app_name/store_name/9 << Get One
//POST /api/app_name/store_name/filter << Filter Without Pagination
//{
//    "column1": ["1", "2"]
//}