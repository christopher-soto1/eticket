<?php
// Archivo de configuración local

// 1 = produccion, 0 = local
define('BASE_GLOBAL', 0); 

define('BASE_URL_PROD', 'http://192.168.1.18/informe_powerb');
define('BASE_URL_LOCAL', 'http://localhost/eticket/correo/verTabla/1');

// Ruta completa a imágenes
define('LOGO_PATH_PROD', BASE_URL_PROD . '/imagenes/Logo_IOPA.png');
define('MINIATURA_PATH_PROD', BASE_URL_PROD . '/imagenes/IOPA_BI.png');

define('LOGO_PATH_LOCAL', BASE_URL_LOCAL . '\imagenes\Logo_IOPA.png');
define('MINIATURA_PATH_LOCAL', BASE_URL_LOCAL . '\imagenes\IOPA_BI.png');

// Ruta absoluta del proyecto en el sistema
define('BASE_PATH', realpath(__DIR__));

// Ruta base actual del proyecto (elige PROD o LOCAL manualmente)
define('BASE_URL', BASE_URL_PROD);

// Ruta a la carpeta de imágenes actual
define('IMG_URL', BASE_URL . '/imagenes/');
define('IMG_PATH', BASE_PATH . '/imagenes/');
