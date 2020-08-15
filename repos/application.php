<?php

namespace Repositories;

include_once $_SERVER['DOCUMENT_ROOT'] . "/db.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/models/application.model.php";

use Data\DataContext;
use Models\Application;

class ApplicationRepository
{
    protected $dbLink;
    protected $TABLE = "applications";

    public function __construct()
    {

        $db = new DataContext();
        $this->dbLink = $db->createContext();

    }

    /**
     * Insert new application record to database
     *
     * @param Application $model
     * @return bool
     */
    public function insert(Application $model): bool
    {

        $sql = "INSERT INTO $this->TABLE (name, description, app_key, app_api_slug) VALUES ('$model->name', '$model->description', '$model->app_key', '$model->app_api_slug')";

        if ($this->dbLink->query($sql) === true) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Updates the existing record to database
     *
     * @param Object $model
     * @param int $id
     * @return bool
     */
    public function update(Object $model, int $id): bool
    {

        if ($model == null) {
            return false;
        }

        $sql = "UPDATE $this->TABLE SET ";

        foreach ($model as $key => $value) {
            $sql .= "$key = '$value'";
        }

        $sql .= " WHERE id = $id";

        if ($this->dbLink->query($sql) === true) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Delete application record from the database.
     *
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool
    {

        $sql = "DELETE FROM $this->TABLE WHERE id=$id";

        if ($this->dbLink->query($sql) === true) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Get the application records by Id
     *
     * @param int $id
     * @return array | null
     */
    public function getById(int $id)
    {

        $sql = "SELECT * FROM $this->TABLE WHERE id = $id";
        $result = $this->dbLink->query($sql);

        if ($result->num_rows > 0) {

            $arr = array();
            while ($row = $result->fetch_assoc()) {
                array_push($arr, new Application($row));
            }

            return $arr;

        }

        return null;

    }

    /**
     * Get all application records from the database.
     *
     * @return array
     */
    public function getAll()
    {

        $sql = "SELECT * FROM $this->TABLE";
        $result = $this->dbLink->query($sql);

        if ($result->num_rows > 0) {

            $arr = array();
            while ($row = $result->fetch_assoc()) {
                array_push($arr, new Application($row));
            }

            return $arr;

        }

        return array();
    }

    /**
     * Get the application record by key
     *
     * @param string $key
     * @return Application
     */
    public function getByKey(string $key): ?Application
    {

        $sql = "SELECT * FROM $this->TABLE WHERE app_key = '$key'";
        $result = $this->dbLink->query($sql);

        if ($result->num_rows > 0) {

            return new Application($result->fetch_assoc());

        }

        return null;
    }

}
