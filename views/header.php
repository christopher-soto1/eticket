<?php
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
$permiso = $this->permiso;
$asignacion = $this->asignacion;

//ini_set('display_errors', 1);
//error_reporting(E_ALL);
ini_set('max_execution_time', 4000);  // 300 segundos = 5 minutos
ini_set('memory_limit', '512M');
//error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);

session_start();

$timeout = 8 * 60 * 60; // 4 horas en segundos

if (!isset($_SESSION['usuario'])) {
    session_unset();
    session_destroy();
    header("Location: " . constant('URL') . "login");
    exit();
}

if (isset($_SESSION['LAST_ACTIVITY']) && 
    (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {

    session_unset();
    session_destroy();
    header("Location: " . constant('URL') . "login");
    exit();
}

// actualizar actividad
$_SESSION['LAST_ACTIVITY'] = time();


?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="<?php echo constant('URL'); ?>public/uploads/logo.png">
  <title>E-Tickets</title>
  <?php
  include_once 'models/usuariosperfil.php';
  $correoModel = new CorreoModel();
  foreach ($this->usuariosperfil as $row) {
    $usuariosperfil = new Usuariosperfil();
    $usuariosperfil = $row;
    $idusuario = $usuariosperfil->id_usuario;
    $menu = $usuariosperfil->menu;
    $habilitado = $usuariosperfil->habilitado;
    $principal = $usuariosperfil->principal;
    //$permiso = $usuariosperfil->permiso;
  }
  // Accede al primer elemento del array (aunque todos los elementos contienen el mismo valor de idusuario)
  $usuariosperfil0 = $this->usuariosperfil[0];
  $idusuario0 = $usuariosperfil0->idusuario;
  $menu = $usuariosperfil0->menu;
  $habilitado = $usuariosperfil0->habilitado;
  $principal = $usuariosperfil0->principal;
  $permiso = $usuariosperfil0->permiso;

  $_SESSION['permiso'] = $permiso;
  $_SESSION['idusuario'] = $idusuario0;

  $permiso = $this->permiso;
  $asignacion = $this->asignacion;

  $usuarios_permitidos = [
                'christopher.soto@iopa.cl',
                'nstuardo@gmail.com',
                'dimas.delmoral@iopa.cl',
                'daniel.navarrete@iopa.cl',
                'marcos.huenchunir@iopa.cl',
                'luis.farias@iopa.cl',
                'catalina.henriquez@iopa.cl'
              ];
  ?>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- Moment.js (requerido por daterangepicker) -->
  <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

  <!-- Daterangepicker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

  <!-- Font Awesome 5.15.4 (compatible con AdminLTE 3.2.0) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">

  <!-- Ionicons (solo si usas los íconos "ion") -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Bootstrap 4.6.2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <!-- AdminLTE 3.2.0 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

  <!-- SweetAlert2 CSS (opcional para alertas) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.min.css">

  <!-- Animate.css (opcional si usas animaciones) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>


  <style>
    .main-sidebar {
      left: 0 !important;
    }

    .content-wrapper {
      margin-left: 0 !important;
      margin-top: -35 !important;

    }
  </style>
</head>

<nav class="navbar navbar-expand navbar-dark bg-primary">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>

    <!-- Link fijo a Inicio -->
    <li class="nav-item d-none d-sm-inline-block ml-3">
        <a href="" class="nav-link font-weight-bold" onclick="reload();" data-tooltip="tooltip" title="Recargar página">
            <i class="fas fa-ticket-alt mr-1"></i> IOPA System <span class="font-weight-light">| E-Tickets</span>
        </a>
    </li>

    <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario'], $usuarios_permitidos)) { ?>

    <li class="nav-item d-none d-md-block">
        <span class="nav-link disabled text-white-50">|</span>
    </li>

    

    <li class="nav-item d-none d-sm-inline-block ml-3">
        <a href="<?= constant('URL'); ?>proyectos/verTabla" class="nav-link font-weight-bold" onclick="reload();" data-tooltip="tooltip" title="Ir a proyectos IOPA">
            <i class="fas fa-tasks mr-1"></i> IOPA System <span class="font-weight-light">| Proyectos IOPA</span>
        </a>
    </li>

    <?php }?>
    

    <!-- Formularios -->
    <?php
    include_once 'models/usuariosperfil.php';
    foreach ($this->usuariosperfil as $row) {
      $usuariosperfil = new Usuariosperfil();
      $usuariosperfil = $row;
      if ($usuariosperfil->principal == "Formularios") { ?>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?php echo constant('URL') . $usuariosperfil->menu; ?>/verPaginacion/1" class="nav-link">
            <i class="fas fa-edit"></i> <?php echo $usuariosperfil->menu; ?>
          </a>
        </li>
    <?php }
    }
    ?>
  </ul>

  <!-- Right navbar -->
  <ul class="navbar-nav ml-auto">

    <!-- Usuario -->
    <li class="nav-item">
      <a class="nav-link" href="#" data-tooltip="tooltip" title="<?= $_SESSION["usuario"]; ?>">
        <i class="fas fa-user-circle"></i> 
        <small class="badge badge-light mr-1"><?= strtoupper($permiso); ?></small>
            
      </a>
    </li>
    

    <!-- Logout -->
    <li class="nav-item">
      <a class="nav-link" href="<?= constant('URL'); ?>login/salir">
        <i class="fas fa-sign-out-alt"></i> Salir
      </a>
    </li>
  </ul>
</nav>

<body class="sidebar-mini layout-navbar-fixed sidebar-collapse sidebar-closed">
