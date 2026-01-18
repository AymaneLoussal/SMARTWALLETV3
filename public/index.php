<?php

use core\App;
session_start();
require_once "../config/config.php";
require_once "../app/core/Database.php";
require_once "../app/core/Model.php";
require_once "../app/core/Controller.php";
require_once "../app/core/App.php";

new App();
