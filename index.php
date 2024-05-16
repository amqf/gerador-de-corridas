<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
define('DATABASE_PATH', './databases/database.db');
// define('DATABASE_PATH', '../databases/database.db');

require_once './vendor/autoload.php';
require_once './web_server.php';