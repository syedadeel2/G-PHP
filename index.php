<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST,DELETE,PUT");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


//** PRE_DEFINED Tables */

define("DOMAINS", "domain_whitelist");
define("STATS", "request_stats");
