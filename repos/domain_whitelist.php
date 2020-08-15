<?php

namespace Repositories;

include_once $_SERVER['DOCUMENT_ROOT'] . "/db.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/models/domain_whitelist.model.php";

use Data\DataContext;
use Models\DomainWhitelist;

class DomainWhitelistRepository
{
    protected $dbLink;
    protected $TABLE = "domain_whitelist";

    public function __construct()
    {

        $db = new DataContext();
        $this->dbLink = $db->createContext();

    }

    /**
     * Insert new Domain Whitelist record to database
     *
     * @param DomainWhitelist $model
     * @return bool
     */
    public function insert(DomainWhitelist $model): bool
    {

        $sql = "INSERT INTO $this->TABLE (application_id, domain, ip_address) VALUES ('$model->application_id', '$model->domain', '$model->ip_address')";

        if ($this->dbLink->query($sql) === true) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Updates the existing record to database
     *
     * @param array $model
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
     * Delete Domain Whitelist record from the database.
     *
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool
    {

        $sql = "DELETE FROM $this->TABLE WHERE id = $id";

        if ($this->dbLink->query($sql) === true) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Get the Domain Whitelist record by Id
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
                array_push($arr, new DomainWhitelist($row));
            }

            return $arr;

        }

        return null;

    }

    /**
     * Get the Domain Whitelist record by Application Id
     *
     * @param int $id
     * @return array | null
     */
    public function getByApplicationId(int $id)
    {

        $sql = "SELECT * FROM $this->TABLE WHERE application_id = $id";
        $result = $this->dbLink->query($sql);

        if ($result->num_rows > 0) {

            $arr = array();
            while ($row = $result->fetch_assoc()) {
                array_push($arr, new DomainWhitelist($row));
            }

            return $arr;

        }

        return null;

    }

    /**
     * Get all Domain Whitelist records from the database.
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
                array_push($arr, new DomainWhitelist($row));
            }

            return $arr;

        }

        return array();
    }

}
