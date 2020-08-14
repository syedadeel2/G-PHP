<?php namespace Repositories;

use Models\Application;

define("APPS", "applications");

class ApplicationRepository
{
 protected $dbLink;

 public function __construct($dbLink)
 {
  $this->dbLink = $dbLink;
 }

 /**
  * Insert new application record to database
  *
  * @param Application $model
  * @return void
  */
 public function insert(Application $model)
 {

  $sql = "INSERT INTO " . APPS . " (name, description, app_key) VALUES ('$model->name', '$model->description', '$model->app_key')";

  if ($this->dbLink->query($sql) === true) {
   return true;
  } else {
   return false;
  }

 }

 /**
  * Updates the existing record to database
  *
  * @param Application $model
  * @return void
  */
 public function update(Application $model)
 {

  $sql = "INSERT INTO " . APPS . " (name, description, app_key) VALUES ('$model->name', '$model->description', '$model->app_key')";

  if ($this->dbLink->query($sql) === true) {
   return true;
  } else {
   return false;
  }

 }

 /**
  * Delete application record from the database.
  *
  * @param Application $model
  * @return void
  */
 public function remove(Application $model)
 {

  $sql = "INSERT INTO " . APPS . " (name, description, app_key) VALUES ('$model->name', '$model->description', '$model->app_key')";

  if ($this->dbLink->query($sql) === true) {
   return true;
  } else {
   return false;
  }

 }

 /**
  * Get the application record by Id
  *
  * @param int $id
  * @return void
  */
 public function getById(int $id)
 {

  $sql    = "SELECT * FROM " . APPS . " WHERE id = $id";
  $result = $this->dbLink->query($sql);

  if ($result->num_rows > 0) {

   return json_encode($result->fetch_assoc());

  } else {

   echo "{}";
  }

 }

 /**
  * Get all application records from the database.
  *
  * @return void
  */
 public function getAll()
 {

  $sql    = "SELECT * FROM " . APPS;
  $result = $this->dbLink->query($sql);

  if ($result->num_rows > 0) {

   return json_encode($result->fetch_assoc());

  } else {

   echo "[]";
  }

 }

}
