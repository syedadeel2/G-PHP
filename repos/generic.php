<?php

namespace Repositories;

include_once $_SERVER['DOCUMENT_ROOT'] . "/db.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/OdataQueryParser.php";

use Data\DataContext;
use Helpers\OdataQueryParser;
use Helpers\Utils;
use Models\DomainWhitelist;

class GenericRepository
{
    protected $dbLink;

    public function __construct()
    {

        $db = new DataContext();
        $this->dbLink = $db->createContext();

    }

    /**
     * Creates table if not exists and
     * Insert new record to database
     *
     * @param Object $model
     * @param string $store_name
     * @return int
     */
    public function insert(Object $model, string $store_name): int
    {

        // generate the table columns string
        $tableColumns = "";
        $insertColumns = "";
        $insertValues = "";
        foreach ($model as $key => $value) {
            $key = str_replace(" ", "_", $key);
            $type = "";
            $nType = gettype($value);

            if ($nType == "boolean") {
                $type = "BIT";
                $insertValues .= $value == true ? "1 ," : "0 ,";
            } else if ($nType == "integer") {
                $type = "INT";
                $insertValues .= "$value ,";
            } else if ($nType == "double") {
                $type = "DECIMAL(19,4)";
                $insertValues .= "$value ,";
            } else if ($nType == "string") {
                strlen($value) > 255 ? $type = "TEXT" : $type = "VARCHAR(255)";

                if (Utils::validateDate($value)) {
                    $type = "DATETIME";
                }

                $insertValues .= "'$value' ,";
            } else if ($nType == "array") {
                $type = "JSON";
                $insertValues .= "'" . json_encode($value) . "' ,";
            } else if ($nType == "object") {
                $type = "JSON";
                $insertValues .= "'" . json_encode($value) . "' ,";
            } else {
                $type = "VARCHAR(255)";
                $insertValues .= "NULL ,";
            }

            $tableColumns .= "`$key` $type null," . "\n";

            $insertColumns .= "$key ,";

        }

        $tableColumns = substr($tableColumns, 0, strlen($tableColumns) - 2);
        $insertColumns = substr($insertColumns, 0, strlen($insertColumns) - 2);
        $insertValues = substr($insertValues, 0, strlen($insertValues) - 2);

        $sql = "CREATE TABLE IF NOT EXISTS `$store_name` (
            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, $tableColumns ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;" . "\n\r";
        // run table query first
        if ($this->dbLink->query($sql) === true) {

            // create insert data query
            $sql = "INSERT INTO `$store_name` ($insertColumns) VALUES ($insertValues);";
            if ($this->dbLink->query($sql) === true) {
                return $this->dbLink->insert_id;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    /**
     * Updates the existing record to database
     *
     * @param Object $model
     * @param int $id
     * @param string $store_name
     * @return bool
     */
    public function update(Object $model, int $id, string $store_name): bool
    {

        if ($model == null) return false;

        $sql = "UPDATE `$store_name` SET ";
        foreach ($model as $key => $value) {
            $key = str_replace(" ", "_", $key);
            $nType = gettype($value);

            if ($nType == "boolean") {
                $sql .= $value == true ? "$key=1," : "$key=0,";
            } else if ($nType == "integer") {
                $sql .= "$key=$value,";
            } else if ($nType == "double") {
                $sql .= "$key = $value,";
            } else if ($nType == "string") {
                $sql .= "$key='$value',";
            } else if ($nType == "array") {
                $sql .= "$key='" . json_encode($value) . "',";
            } else if ($nType == "object") {
                $sql .= "$key='" . json_encode($value) . "',";
            } else {
                $sql .= "$key=NULL,";
            }

        }

        $sql = substr($sql, 0, strlen($sql) - 1);
        $sql .= " WHERE id=$id";
        if ($this->dbLink->query($sql) === true) return true;
        return false;

    }

    /**
     * Delete record from the store table.
     *
     * @param int $id
     * @param string $store_name
     * @return bool
     */
    public function remove(int $id, string $store_name): bool
    {
        $sql = "DELETE FROM `$store_name` WHERE id = $id";
        if ($this->dbLink->query($sql) === true) return true;
        return false;
    }

    /**
     * Delete the table form database.
     * @param string $store_name
     * @return bool
     */
    public function dropTable(string $store_name): bool
    {
        $sql = "DROP TABLE `$store_name`";
        if ($this->dbLink->query($sql) === true) return true;
        return false;
    }

    /**
     * Truncate the table from database.
     * @param string $store_name
     * @return bool
     */
    public function truncateTable(string $store_name): bool
    {
        $sql = "truncate TABLE `$store_name`";
        if ($this->dbLink->query($sql) === true) return true;
        return false;
    }

    /**
     * Get the Domain Whitelist record by Id
     *
     * @param int $id
     * @param string $store_name
     * @return array | null
     */
    public function getById(int $id, string $store_name)
    {
        $cols = "*";
        if (isset($_GET["cols"]) && !empty($_GET["cols"])) $cols = $_GET["cols"];

        $sql = "SELECT $cols FROM `$store_name` WHERE id = $id";
        $result = $this->dbLink->query($sql);

        if ($result->num_rows > 0) return $result->fetch_assoc();

        return null;
    }

    /**
     * Get all store records from the database
     *
     * @param string $store_name
     * @param Object|null $filters
     * @return array
     */
    public function getAll(string $store_name, Object $filters = null)
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $oData = OdataQueryParser::parse($actual_link, true);


        $cols = array_key_exists("select", $oData) ? implode(",", $oData["select"]) : "*";

        $sql = "SELECT $cols, count(*) over () as total_records FROM `$store_name`";

        // where if available
        $sql .= array_key_exists("filter", $oData) ? " WHERE " . implode("AND", array_map(function ($e) {
                $left = $e["left"];
                $operator = $e["operator"];
                $right = $e["right"];

                $q = "$left $operator";
                $v = is_array($right) ? "(" . implode(",", $right) . ")" : (($operator == "LIKE") ? "'%$right%'" : $right);
                return "$q $v";
            }, $oData["filter"])) : "";


        // order by if available
        $sql .= array_key_exists("orderBy", $oData) ? " ORDER BY " . implode(",", array_map(function ($e) {
                return $e["property"] . " " . $e["direction"];
            }, $oData["orderBy"])) : "";

        // add pagination if available
        $isPagination = false;
        $start = array_key_exists("skip", $oData) ? $oData["skip"] : null;
        $length = array_key_exists("top", $oData) ? $oData["top"] : null;

        if ($start != null && $length != null) {

            $sql .= " LIMIT $length OFFSET $start";
            $isPagination = true;

        } else if ($start == null && $length != null) {

            $sql .= " LIMIT $length";
            $isPagination = true;

        } else if ($start != null && $length == null) {

            $sql .= " LIMIT $start OFFSET $start";
            $isPagination = true;

        }


        $result = $this->dbLink->query($sql);

        if ($result == null) {
            http_response_code(503);
            die(json_encode(array("message" => "Error in query, if you have specified the columns make sure they should matched with the existing columns", "status" => false)));
        }

        if ($result->num_rows > 0) {

            $arr = array();
            $obj = new \stdClass();
            $obj->total_records = 0;
            $cnt = 0;
            while ($row = $result->fetch_assoc()) {

                if ($cnt == 0) $obj->total_records = (int)$row["total_records"];
                unset($row["total_records"]); // remove this from data object

                array_push($arr, $row);
                $cnt++;
            }

            $obj->data = $arr;
            $obj->is_more = false;

            if ($isPagination) {
                $obj->length = $length;
                $obj->start = $start;
                $total = $start + $length;
                if ($total >= $obj->total_records) {
                    $obj->is_more = false;
                } else if ($total < $obj->total_records) {
                    $obj->is_more = true;
                }
            }

            return $obj;
        }

        return array();
    }

}
