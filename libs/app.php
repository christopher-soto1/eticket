<?php
require_once 'controllers/errores.php';
class App
{
  function __construct()
  {
    //obtengo url
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    //que existe un solo / por division
    $url = rtrim($url, '/');
    //obtener lo valores de los separadores
    $url = explode('/', $url);
    //cuando se ingresa sin definir controlador
    if (empty($url[0])) {
      $archivoController = 'controllers/login.php';
      require_once $archivoController;
      $controller = new Login();
      $controller->loadModel('login');
      $controller->render();
      return false;
    }
    $archivoController = 'controllers/' . $url[0] . '.php';
    if (file_exists($archivoController)) {
      require_once $archivoController;
      $controller = new $url[0];
      $controller->loadModel($url[0]);
      //numero de elementos del arreglo
      $nparam = sizeof($url);
      if ($nparam > 1) {
        if ($nparam > 2) {
          $param = [];
          for ($i = 2; $i < $nparam; $i++) {
            array_push($param, $url[$i]);
          }
          $controller->{$url[1]}($param);
        } else {
          $controller->{$url[1]}();
        }
      } else {
        $controller->render();
      }
    } else {
      //si no encuentra el controlador
      $controller = new Errores();
    }
  }
}
?>