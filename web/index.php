<?php
ini_set('display_errors', 1);

date_default_timezone_set("GMT");

require_once __DIR__ . '/../vendor/autoload.php';

use Api\App;

$app = new App();

require __DIR__.'/../config/dev.php';

$app->run();
?>