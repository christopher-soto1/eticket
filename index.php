<?php
error_reporting(E_ALL); 
ini_set('ignore_repeated_errors', TRUE);
ini_set('display_errors', FALSE);
ini_set('log_errors', TRUE);
ini_set("error_log", "/var/www/html/Tabla/php-error-mvc.log");
error_log( "Log de Errores en App!" );

require_once 'libs/database.php';
require_once 'libs/controller.php';
require_once 'libs/view.php';
require_once 'libs/model.php';
require_once 'libs/app.php';
require_once 'config/config.php';
$app=new App();
?>
