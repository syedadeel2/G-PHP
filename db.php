<?php

namespace Data;

use mysqli;

define("DB_HOST", "127.0.0.1");
define("DB_NAME", "gphp");
define("DB_USER", "root");
define("DB_PASS", "password");

class DataContext
{

    public function __construct()
    {
    }

    public function createContext()
    {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
        }

        return $mysqli;
    }

}
